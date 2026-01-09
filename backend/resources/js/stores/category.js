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
        async index(params = {}) {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.get("/admin/category", { params });
                if (res.status === 200) {
                    this.categories = res.data?.data ?? [];
                }
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },


        async status(categoryID, status) {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.put(`/admin/category/${categoryID}/status`,{
                    status: status
                });
                // if (res.status === 200) {
                //     this.categories = res.data?.data ?? [];
                // }
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },

        async bulkDelete(ids) {
            this.loading = true;
            this.error = null;
            try {
                await axiosInstance.delete("/admin/category/multiple-delete", {
                    data: { ids },
                });
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },




    },
});
