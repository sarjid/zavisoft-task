import { defineStore } from "pinia";
import axiosInstance from "@/services/axiosService";

const extractData = (response) => {
    if (response?.data?.data) {
        return response.data.data;
    }
    if (Array.isArray(response?.data)) {
        return response.data;
    }
    return [];
};

export const useShopStore = defineStore("shop", {
    state: () => ({
        categories: [],
        products: [],
        productDetail: null,
        productsPage: 1,
        productsLastPage: 1,
        productsHasMore: true,
        categoriesLoading: false,
        productsLoading: false,
        productDetailLoading: false,
        categoriesError: null,
        productsError: null,
        productDetailError: null,
    }),
    actions: {
        async fetchCategories(params = {}) {
            this.categoriesLoading = true;
            this.categoriesError = null;
            try {
                const response = await axiosInstance.get("/categories", { params });
                this.categories = extractData(response);
            } catch (error) {
                this.categoriesError = error;
            } finally {
                this.categoriesLoading = false;
            }
        },
        async fetchProducts(params = {}, options = {}) {
            const { append = false } = options;
            const page = params.page ?? 1;
            this.productsLoading = true;
            this.productsError = null;
            try {
                const response = await axiosInstance.get("/products", { params: { ...params, page } });
                const items = extractData(response);
                const meta = response?.data?.meta;

                if (page === 1 || !append) {
                    this.products = items;
                } else {
                    this.products = [...this.products, ...items];
                }

                if (meta) {
                    this.productsPage = meta.current_page ?? page;
                    this.productsLastPage = meta.last_page ?? page;
                    this.productsHasMore = this.productsPage < this.productsLastPage;
                } else {
                    this.productsPage = page;
                    this.productsLastPage = page;
                    this.productsHasMore = items.length > 0;
                }
            } catch (error) {
                this.productsError = error;
            } finally {
                this.productsLoading = false;
            }
        },
        async fetchProductDetail(slug) {
            if (!slug) {
                this.productDetail = null;
                return;
            }
            this.productDetailLoading = true;
            this.productDetailError = null;
            try {
                const response = await axiosInstance.get(`/products/slug/${slug}`);
                this.productDetail = response?.data?.data?.product ?? null;
            } catch (error) {
                this.productDetailError = error;
            } finally {
                this.productDetailLoading = false;
            }
        },
    },
});
