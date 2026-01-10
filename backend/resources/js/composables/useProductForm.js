import { computed, onUnmounted, reactive, ref, watch } from "vue";
import { useProduct } from "@/stores/product";
import useNotify from "@/composables/useNotify";

export const useProductForm = () => {
    const productStore = useProduct();
    const notify = useNotify();

    const form = reactive({
        name: "",
        category: null,
        hasVariation: false,
        attributes: [],
        attributeValues: {},
        variations: [],
        price: "",
        discount: "",
        discountType: "fixed",
        quantity: "",
        sku: "",
        description: "",
        statusActive: true,
        thumbnail: null,
        gallery: [],
    });

    const categoryOptions = ref([]);
    const attributeOptions = ref([]);
    const attributeValueOptions = ref({});
    const loadingOptions = ref(false);
    const loadingAttributeValues = ref(false);
    const expandedVariationKey = ref(null);
    const lastUnitPrice = ref("");
    const galleryPreviews = ref([]);

    const revokeGalleryPreviews = () => {
        galleryPreviews.value.forEach((preview) => {
            if (preview.file && preview.url) {
                URL.revokeObjectURL(preview.url);
            }
        });
        galleryPreviews.value = [];
    };

    watch(
        () => form.gallery,
        (nextGallery) => {
            revokeGalleryPreviews();
            if (Array.isArray(nextGallery)) {
                galleryPreviews.value = nextGallery.map((file) => {
                    if (file instanceof File) {
                        return {
                            name: file?.name || "Image",
                            url: URL.createObjectURL(file),
                            file,
                        };
                    }
                    if (typeof file === "string") {
                        return {
                            name: "Image",
                            url: file,
                            file: null,
                        };
                    }
                    return { name: "Image", url: "", file: null };
                });
            }
        },
        { deep: true }
    );

    onUnmounted(() => {
        revokeGalleryPreviews();
    });

    const removeGalleryImage = (index) => {
        if (!Array.isArray(form.gallery)) {
            return;
        }
        const removed = form.gallery.splice(index, 1);
        const preview = galleryPreviews.value.splice(index, 1)[0];
        if (preview?.file && preview.url) {
            URL.revokeObjectURL(preview.url);
        }
        if (removed?.[0] instanceof File) {
            form.gallery = [...form.gallery];
        }
    };

    const loadCreateData = async () => {
        loadingOptions.value = true;
        try {
            const data = await productStore.fetchCreateData();
            categoryOptions.value = data?.categories ?? [];
            attributeOptions.value = data?.attributes ?? [];
        } catch (error) {
            notify.error("Failed to load product options");
        } finally {
            loadingOptions.value = false;
        }
    };

    const getAttributeIds = (items) => {
        if (!Array.isArray(items)) {
            return [];
        }
        return items
            .map((item) => (typeof item === "object" ? item?.id ?? item?.value : item))
            .filter(Boolean);
    };

    const syncAttributeValues = (ids) => {
        const nextValues = { ...form.attributeValues };
        ids.forEach((id) => {
            if (!Array.isArray(nextValues[id])) {
                nextValues[id] = [];
            }
        });
        Object.keys(nextValues).forEach((id) => {
            if (!ids.includes(Number(id)) && !ids.includes(id)) {
                delete nextValues[id];
            }
        });
        form.attributeValues = nextValues;
    };

    const loadAttributeValues = async (ids) => {
        if (!ids.length) {
            attributeValueOptions.value = {};
            form.attributeValues = {};
            return;
        }
        loadingAttributeValues.value = true;
        try {
            const data = await productStore.fetchAttributeValues(ids);
            attributeValueOptions.value = data?.attribute_values ?? {};
            syncAttributeValues(ids);
        } catch (error) {
            notify.error("Failed to load attribute values");
        } finally {
            loadingAttributeValues.value = false;
        }
    };

    const isColorAttribute = (attribute) => attribute?.name?.toLowerCase() === "color";

    const toggleVariation = (key) => {
        expandedVariationKey.value =
            expandedVariationKey.value === key ? null : key;
    };

    const buildVariationCombos = (selectedAttributes, selectedValuesMap) => {
        const groups = selectedAttributes
            .map((attribute) => {
                const values = selectedValuesMap?.[attribute.id] ?? [];
                return {
                    attribute,
                    values: Array.isArray(values) ? values : [],
                };
            })
            .filter((group) => group.values.length);

        if (!groups.length) {
            return [];
        }

        return groups.reduce((acc, group) => {
            if (!acc.length) {
                return group.values.map((value) => [{
                    attribute: group.attribute,
                    value,
                }]);
            }
            const next = [];
            acc.forEach((combo) => {
                group.values.forEach((value) => {
                    next.push([
                        ...combo,
                        {
                            attribute: group.attribute,
                            value,
                        },
                    ]);
                });
            });
            return next;
        }, []);
    };

    const variationCombos = computed(() =>
        buildVariationCombos(form.attributes, form.attributeValues)
    );

    const buildVariationKey = (combo) =>
        combo.map((item) => `${item.attribute.id}:${item.value.id}`).join("|");

    const syncVariations = (seedMap = new Map()) => {
        const existing = new Map();
        form.variations.forEach((variation) => {
            existing.set(variation.key, variation);
        });
        seedMap.forEach((value, key) => {
            if (!existing.has(key)) {
                existing.set(key, value);
            }
        });

        const next = variationCombos.value.map((combo) => {
            const key = buildVariationKey(combo);
            const label = combo
                .map((item) => item.value?.value ?? "")
                .filter(Boolean)
                .join(" / ");
            const saved = existing.get(key);
            return {
                key,
                label,
                combo,
                sku: saved?.sku ?? "",
                price: saved?.price ?? form.price ?? "",
                quantity: saved?.quantity ?? "",
                image: saved?.image ?? null,
            };
        });

        form.variations = next;
        if (!next.length) {
            expandedVariationKey.value = null;
            return;
        }
        if (!next.find((item) => item.key === expandedVariationKey.value)) {
            expandedVariationKey.value = next[0]?.key ?? null;
        }
    };

    const resetVariations = () => {
        form.attributes = [];
        form.attributeValues = {};
        form.variations = [];
        attributeValueOptions.value = {};
        expandedVariationKey.value = null;
    };

    const buildFormData = () => {
        const formData = new FormData();
        formData.append("name", form.name.trim());
        if (form.category?.id) {
            formData.append("category_id", form.category.id);
        }
        formData.append("unit_price", form.price || 0);
        formData.append("discount", form.discount || 0);
        formData.append("discount_type", form.discountType || "fixed");
        formData.append("description", form.description || "");
        if (!(form.hasVariation && form.variations.length)) {
            formData.append("current_stock", form.quantity || 0);
            formData.append("sku", form.sku || "");
        }
        formData.append("status", form.statusActive ? 1 : 0);

        if (form.thumbnail instanceof File) {
            formData.append("thumbnail", form.thumbnail);
        }

        if (Array.isArray(form.gallery)) {
            form.gallery.forEach((file) => {
                if (file instanceof File) {
                    formData.append("images[]", file);
                }
            });
        }

        if (form.hasVariation && form.variations.length) {
            form.variations.forEach((variation, index) => {
                formData.append(`variations[${index}][sku]`, variation.sku || "");
                formData.append(`variations[${index}][price]`, variation.price || 0);
                formData.append(`variations[${index}][quantity]`, variation.quantity || 0);

                if (variation.image instanceof File) {
                    formData.append(`variations[${index}][image]`, variation.image);
                }

                variation.combo.forEach((item, attrIndex) => {
                    formData.append(
                        `variations[${index}][attributes][${attrIndex}][attribute_id]`,
                        item.attribute?.id ?? ""
                    );
                    formData.append(
                        `variations[${index}][attributes][${attrIndex}][attribute_value_id]`,
                        item.value?.id ?? ""
                    );
                });
            });
        }

        return formData;
    };

    return {
        form,
        categoryOptions,
        attributeOptions,
        attributeValueOptions,
        loadingOptions,
        loadingAttributeValues,
        expandedVariationKey,
        lastUnitPrice,
        galleryPreviews,
        loadCreateData,
        loadAttributeValues,
        getAttributeIds,
        syncVariations,
        resetVariations,
        variationCombos,
        isColorAttribute,
        toggleVariation,
        buildFormData,
        removeGalleryImage,
    };
};
