import { computed, onMounted, ref, watch } from "vue";
import { storeToRefs } from "pinia";
import { useRoute } from "vue-router";
import { useShopStore } from "@/stores/shop";
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

export const useProductDetail = (options = {}) => {
    const { relatedPerPage = 12 } = options;

    const route = useRoute();
    const shopStore = useShopStore();
    const { categories, productDetail, productDetailLoading } =
        storeToRefs(shopStore);

    const selectedImage = ref(null);
    const selectedOptions = ref({});

    const galleryImages = computed(() => {
        const images = [];
        if (productDetail.value?.thumbnail) {
            images.push(productDetail.value.thumbnail);
        }
        if (Array.isArray(productDetail.value?.images)) {
            images.push(...productDetail.value.images);
        }
        const variantImages = (productDetail.value?.variants || [])
            .map((variant) => variant.image)
            .filter(Boolean);
        return [...new Set([...images, ...variantImages])];
    });

    const selectOption = (option, value) => {
        selectedOptions.value = {
            ...selectedOptions.value,
            [option.attribute_name]: value.value,
        };
    };

    const selectedVariant = computed(() => {
        const options = productDetail.value?.options || [];
        if (!options.length) {
            return null;
        }
        const required = options.map((option) => option.attribute_name);
        if (required.some((name) => !selectedOptions.value[name])) {
            return null;
        }
        return (productDetail.value?.variants || []).find((variant) =>
            required.every((name) =>
                variant.attributes?.some(
                    (attribute) =>
                        attribute.attribute_name === name &&
                        attribute.attribute_value === selectedOptions.value[name]
                )
            )
        );
    });

    const currentPrice = computed(() => {
        return (
            selectedVariant.value?.current_price ??
            selectedVariant.value?.price ??
            productDetail.value?.current_price ??
            productDetail.value?.unit_price ??
            null
        );
    });

    const unitPrice = computed(() => productDetail.value?.unit_price ?? null);

    const saveAmount = computed(() => {
        const unit = Number(unitPrice.value);
        const current = Number(currentPrice.value);
        if (Number.isFinite(unit) && Number.isFinite(current)) {
            return Math.max(unit - current, 0);
        }
        return productDetail.value?.discount ?? 0;
    });

    const isOutOfStock = computed(() => {
        const variant = selectedVariant.value;
        if (variant) {
            return Number(variant.quantity ?? 0) <= 0;
        }
        const stock = productDetail.value?.stock;
        if (typeof stock === "number") {
            return stock <= 0;
        }
        const status = productDetail.value?.stock_status;
        return status ? String(status).toLowerCase().includes("out") : false;
    });

    const stockText = computed(() => {
        if (selectedVariant.value) {
            return isOutOfStock.value ? "Stock out" : "In stock";
        }
        if (typeof productDetail.value?.stock === "number") {
            return productDetail.value.stock <= 0 ? "Stock out" : "In stock";
        }
        return productDetail.value?.stock_status || "";
    });

    const activeImage = computed(() => {
        return (
            selectedVariant.value?.image ||
            selectedImage.value ||
            productDetail.value?.thumbnail ||
            ""
        );
    });

    const relatedProducts = ref([]);

    const fetchRelatedProducts = async () => {
        const categorySlug = productDetail.value?.category?.slug;
        if (!categorySlug) {
            relatedProducts.value = [];
            return;
        }
        try {
            const response = await axiosInstance.get("/products", {
                params: { category_slug: categorySlug, perPage: relatedPerPage, page: 1 },
            });
            const items = extractData(response);
            relatedProducts.value = items.filter(
                (product) => product.id !== productDetail.value?.id
            );
        } catch (error) {
            relatedProducts.value = [];
        }
    };

    watch(productDetail, () => {
        selectedImage.value = galleryImages.value[0] || null;
        selectedOptions.value = {};
        fetchRelatedProducts();
    });

    watch(
        () => route.params.slug,
        async (slug) => {
            await shopStore.fetchProductDetail(slug);
        }
    );

    onMounted(async () => {
        await shopStore.fetchCategories();
        await shopStore.fetchProductDetail(route.params.slug);
    });

    return {
        categories,
        productDetail,
        productDetailLoading,
        selectedImage,
        selectedOptions,
        galleryImages,
        selectedVariant,
        currentPrice,
        unitPrice,
        saveAmount,
        isOutOfStock,
        stockText,
        activeImage,
        relatedProducts,
        selectOption,
    };
};
