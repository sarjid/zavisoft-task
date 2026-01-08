import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";
import { useToken } from "@/stores/token";

export const useAuth = defineStore("adminAuth", {
    state: () => ({
        user: {},
        isLggedIn: false,
        loading: false,
    }),

    persist: {
        paths: ["user", "isLggedIn"],
    },

    getters: {
        getUser: (state) => {
            return state.user;
        },
        getAuthStatus: (state) => {
            return state.isLggedIn;
        },
    },

    actions: {
        async login(formData) {
            try {
                const res = await axiosInstance.post("/admin/login", formData);
                if (res.status === 200) {
                    this.setAuthInfo(res.data?.data);
                    return res.data?.data;
                }
            } catch (error) {
                if (error.response.data) {
                    throw error.response.data.errors;
                }
            }
        },

        async logout() {
            this.loading = true;
            try {
                const res = await axiosInstance.post("/admin/logout");
                if (res.status === 200) {
                    this.removeAuthInfo();
                    return res;
                }
            } catch (error) {
                if (error.response) {
                    throw error.resonpse;
                }
            } finally {
                this.loading = false;
            }
        },

        setAuthInfo(data) {
            const token = useToken();
            this.user = data?.user;
            token.setToken(data?.token);
            this.isLggedIn = true;
        },

        removeAuthInfo() {
            const token = useToken();
            token.removeToken();
            this.$reset();
        },
    },
});
