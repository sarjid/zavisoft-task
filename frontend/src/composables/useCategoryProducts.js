import { computed, onBeforeUnmount, onMounted, ref, watch } from "vue";
import { storeToRefs } from "pinia";
import { useRoute, useRouter } from "vue-router";
import { useShopStore } from "@/stores/shop";

export const useCategoryProducts = (options = {}) => {
    const {
        perPage = 8,
        routeName = "home",
        queryKey = "category",
        rootMargin = "200px",
    } = options;

    const shopStore = useShopStore();
    const route = useRoute();
    const router = useRouter();
    const { categories, products, productsLoading, productsHasMore, productsPage } =
        storeToRefs(shopStore);

    const infiniteScrollSentinel = ref(null);
    const selectedCategorySlug = ref(null);
    const sortOrder = ref("");
    let observer = null;

    const selectedCategoryId = computed(() => {
        if (!selectedCategorySlug.value) {
            return null;
        }
        return (
            categories.value.find(
                (category) => category.slug === selectedCategorySlug.value
            )?.id ?? null
        );
    });

    const fetchProductsPage = (page) => {
        const params = { page, perPage };
        if (selectedCategorySlug.value) {
            params.category_slug = selectedCategorySlug.value;
        }
        if (sortOrder.value) {
            params.sort = sortOrder.value;
        }
        return shopStore.fetchProducts(params, { append: page > 1 });
    };

    const selectCategory = async (category) => {
        const nextSlug = category?.slug ?? null;
        selectedCategorySlug.value = nextSlug;
        await router.push({
            name: routeName,
            query: nextSlug ? { [queryKey]: nextSlug } : {},
        });
        await fetchProductsPage(1);
    };

    const clearCategory = async () => {
        selectedCategorySlug.value = null;
        await router.push({ name: routeName, query: {} });
        await fetchProductsPage(1);
    };

    const setSortOrder = async (order) => {
        sortOrder.value = order || "";
        await fetchProductsPage(1);
    };

    onMounted(async () => {
        await shopStore.fetchCategories();
        const initialCategory =
            typeof route.query[queryKey] === "string" ? route.query[queryKey] : null;
        selectedCategorySlug.value = initialCategory;
        await fetchProductsPage(1);

        observer = new IntersectionObserver(
            (entries) => {
                if (entries[0]?.isIntersecting) {
                    if (!productsLoading.value && productsHasMore.value) {
                        fetchProductsPage(productsPage.value + 1);
                    }
                }
            },
            { rootMargin }
        );

        if (infiniteScrollSentinel.value) {
            observer.observe(infiniteScrollSentinel.value);
        }
    });

    onBeforeUnmount(() => {
        if (observer) {
            observer.disconnect();
            observer = null;
        }
    });

    watch(
        () => route.query[queryKey],
        (value) => {
            const nextSlug = typeof value === "string" ? value : null;
            if (nextSlug !== selectedCategorySlug.value) {
                selectedCategorySlug.value = nextSlug;
                fetchProductsPage(1);
            }
        }
    );

    return {
        categories,
        products,
        productsLoading,
        productsHasMore,
        productsPage,
        selectedCategorySlug,
        selectedCategoryId,
        sortOrder,
        infiniteScrollSentinel,
        fetchProductsPage,
        selectCategory,
        clearCategory,
        setSortOrder,
    };
};
