import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";

export const useCategory = defineStore("category", {
    state: () => ({
        categories: [],
        loading: false,
    }),


    getters: {
        getCategories: (state) => {
            return state.categories;
        },
    },

    actions: {
        async index() {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.get("/admin/category");
                if (res.status === 200) {
                    this.categories = res.data?.data ?? [];
                }
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },

    },
});
