import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";

export const useProduct = defineStore("product", {
    state: () => ({
        products: [],
        loading: false,
    }),


    getters: {
        getProducts: (state) => {
            return state.products;
        },
    },

    actions: {
        async index(params = {}) {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.get("/admin/products", { params });
                if (res.status === 200) {
                    this.products = res.data?.data ?? [];
                }
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },


        async status(productID, status) {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.put(`/admin/products/${productID}/status`,{
                    status: status
                });
                // if (res.status === 200) {
                //     this.products = res.data?.data ?? [];
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
                await axiosInstance.delete("/admin/products/multiple-delete", {
                    data: { ids },
                });
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },

        async store(payload) {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.post("/admin/products", payload, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                return res?.data;
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },

        async update(productId, payload) {
            this.loading = true;
            this.error = null;
            try {
                if (payload instanceof FormData) {
                    payload.append("_method", "PUT");
                }
                const res = await axiosInstance.post(`/admin/products/${productId}`, payload, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                return res?.data;
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },

        async fetchCreateData() {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.get("/admin/products/create-data");
                return res?.data?.data ?? {};
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },

        async fetchAttributeValues(attributeIds = []) {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.get("/admin/products/attribute-values", {
                    params: { attribute_ids: attributeIds },
                });
                return res?.data?.data ?? {};
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },

        async show(productId) {
            this.loading = true;
            this.error = null;
            try {
                const res = await axiosInstance.get(`/admin/products/${productId}`);
                return res?.data?.data ?? {};
            } catch (error) {
                throw error?.response?.data?.errors || error;
            } finally {
                this.loading = false;
            }
        },




    },
});
