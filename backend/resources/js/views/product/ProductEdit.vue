<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { ActionButton, PrimaryButton } from "@/components/button";
import { FileInput, FormLabel, MultiSelectInput, PlainTextInput, SelectInput, Switch, TextEditor } from "@/components/form";
import { ArrowLeft, ChevronDown, ChevronUp, Image } from "lucide-vue-next";
import { useProduct } from "@/stores/product";
import useNotify from "@/composables/useNotify";
import { useProductForm } from "@/composables/useProductForm";

const router = useRouter();
const route = useRoute();
const productStore = useProduct();
const notify = useNotify();

const {
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
    isColorAttribute,
    toggleVariation,
    buildFormData,
    removeGalleryImage,
} = useProductForm();

const loadingProduct = ref(false);
const saving = ref(false);
const hydrating = ref(false);

const discountTypeOptions = [
    { value: "fixed", label: "Fixed" },
    { value: "percent", label: "Percent" },
];

const productId = computed(() => route.params.productId);

watch(
    () => form.attributes,
    (nextAttributes) => {
        if (hydrating.value) {
            return;
        }
        const ids = getAttributeIds(nextAttributes);
        loadAttributeValues(ids);
    },
    { deep: true }
);

watch(
    () => form.attributeValues,
    () => {
        if (hydrating.value) {
            return;
        }
        syncVariations();
    },
    { deep: true }
);

watch(
    () => form.price,
    (nextPrice) => {
        if (!(form.hasVariation && form.variations.length)) {
            return;
        }
        form.variations = form.variations.map((variation) => {
            const shouldSync = !variation.price || variation.price === lastUnitPrice.value;
            return {
                ...variation,
                price: shouldSync ? (nextPrice || "") : variation.price,
            };
        });
        lastUnitPrice.value = nextPrice || "";
    }
);

watch(
    () => form.hasVariation,
    (enabled) => {
        if (!enabled) {
            resetVariations();
        }
    }
);

const buildVariationKeyFromVariant = (variant, orderedAttributes) =>
    orderedAttributes
        .map((attribute) => {
            const match = variant.attributes?.find(
                (item) => item.attribute_id === attribute.id
            );
            if (!match?.attribute_value_id) {
                return null;
            }
            return `${attribute.id}:${match.attribute_value_id}`;
        })
        .filter(Boolean)
        .join("|");

const hydrateProduct = async () => {
    loadingProduct.value = true;
    hydrating.value = true;
    try {
        const data = await productStore.show(productId.value);
        const product = data?.product;
        if (!product) {
            notify.error("Product not found");
            router.back();
            return;
        }

        form.name = product.name ?? "";
        const categoryMatch = categoryOptions.value.find(
            (option) => option.id === product.category?.id
        );
        form.category = categoryMatch ?? product.category ?? null;
        form.hasVariation = Boolean(product.has_variant || product.variants?.length);
        form.price = product.unit_price ?? "";
        form.discount = product.discount ?? "";
        form.discountType = product.discount_type ?? "fixed";
        form.quantity = product.stock ?? "";
        form.sku = product.sku ?? "";
        form.description = product.description ?? "";
        form.statusActive = String(product.status || "").toLowerCase() === "active";
        form.thumbnail = product.thumbnail ?? null;
        form.gallery = Array.isArray(product.images) ? product.images : [];
        lastUnitPrice.value = form.price || "";

        if (form.hasVariation && Array.isArray(product.variants) && product.variants.length) {
            const attributeIdSet = new Set();
            const selectedValueMap = new Map();
            product.variants.forEach((variant) => {
                (variant.attributes || []).forEach((attribute) => {
                    if (!attribute.attribute_id || !attribute.attribute_value_id) {
                        return;
                    }
                    attributeIdSet.add(attribute.attribute_id);
                    if (!selectedValueMap.has(attribute.attribute_id)) {
                        selectedValueMap.set(attribute.attribute_id, new Set());
                    }
                    selectedValueMap.get(attribute.attribute_id).add(attribute.attribute_value_id);
                });
            });

            const attributeIds = Array.from(attributeIdSet);
            form.attributes = attributeOptions.value.filter((option) =>
                attributeIdSet.has(option.id)
            );
            await loadAttributeValues(attributeIds);

            const nextValues = {};
            attributeIds.forEach((id) => {
                const options = attributeValueOptions.value[id] || [];
                const selected = selectedValueMap.get(id) || new Set();
                nextValues[id] = options.filter((option) => selected.has(option.id));
            });
            form.attributeValues = nextValues;

            const seedMap = new Map();
            product.variants.forEach((variant) => {
                const key = buildVariationKeyFromVariant(variant, form.attributes);
                if (!key) {
                    return;
                }
                seedMap.set(key, {
                    key,
                    sku: variant.sku ?? "",
                    price: variant.price ?? "",
                    quantity: variant.quantity ?? "",
                    image: variant.image ?? null,
                });
            });

            syncVariations(seedMap);
        }
    } catch (error) {
        notify.error("Failed to load product");
    } finally {
        hydrating.value = false;
        loadingProduct.value = false;
    }
};

onMounted(async () => {
    await loadCreateData();
    await hydrateProduct();
});

const updateProduct = async () => {
    if (!form.name.trim()) {
        notify.error("Product name is required");
        return;
    }
    if (!form.price) {
        notify.error("Unit price is required");
        return;
    }

    saving.value = true;
    try {
        const payload = buildFormData();
        await productStore.update(productId.value, payload);
        notify.success("Product updated");
        router.back();
    } catch (error) {
        notify.error("Failed to update product");
    } finally {
        saving.value = false;
    }
};

const goBack = () => {
    router.back();
};
</script>

<template>
    <section class="min-h-screen bg-body px-6 py-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <ActionButton type="button" @click="goBack">
                    <ArrowLeft class="h-4 w-4" />
                    Back
                </ActionButton>
                <h2 class="mt-4 text-2xl font-semibold text-slate-900">Edit Product</h2>
                <p class="text-sm text-slate-500">Update details, pricing, and inventory for this product.</p>
            </div>
        </div>

        <div v-if="loadingProduct" class="mt-6 grid gap-6 lg:grid-cols-3 animate-pulse">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-40 rounded bg-slate-200"></div>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="h-10 rounded bg-slate-200 md:col-span-2"></div>
                        <div class="h-10 rounded bg-slate-200 md:col-span-1"></div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="h-4 w-44 rounded bg-slate-200"></div>
                        <div class="h-6 w-10 rounded-full bg-slate-200"></div>
                    </div>
                    <div class="mt-4 h-10 rounded bg-slate-200"></div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-48 rounded bg-slate-200"></div>
                    <div class="mt-4 space-y-4">
                        <div class="h-10 rounded bg-slate-200"></div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="h-10 rounded bg-slate-200"></div>
                            <div class="h-10 rounded bg-slate-200"></div>
                        </div>
                        <div class="h-10 rounded bg-slate-200"></div>
                        <div class="h-10 rounded bg-slate-200"></div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-52 rounded bg-slate-200"></div>
                    <div class="mt-4 h-32 rounded bg-slate-200"></div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-32 rounded bg-slate-200"></div>
                    <div class="mt-4 space-y-4">
                        <div class="h-10 rounded bg-slate-200"></div>
                        <div class="h-10 rounded bg-slate-200"></div>
                        <div class="h-24 rounded bg-slate-200"></div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="h-4 w-32 rounded bg-slate-200"></div>
                    <div class="mt-4 h-10 rounded bg-slate-200"></div>
                </div>
            </div>
        </div>

        <div v-else class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 text-sm font-semibold text-slate-700">Product Information</div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <FormLabel>Product Name *</FormLabel>
                            <PlainTextInput v-model="form.name" placeholder="Product name" type="text" />
                        </div>
                        <div class="md:col-span-1">
                            <FormLabel>Category</FormLabel>
                            <MultiSelectInput v-model="form.category" :options="categoryOptions"
                                placeholder="Choose category" label="name" track-by="id" :disabled="loadingOptions" />
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-sm font-semibold text-slate-700">Product Variation</div>
                        <Switch v-model="form.hasVariation" />
                    </div>
                    <div v-if="form.hasVariation" class="grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <FormLabel>Attributes</FormLabel>
                            <MultiSelectInput v-model="form.attributes" :options="attributeOptions"
                                placeholder="Choose attributes" multiple label="name" track-by="id"
                                :disabled="loadingOptions" />
                        </div>
                    </div>
                    <div v-if="form.hasVariation && form.attributes.length" class="mt-4 space-y-4">
                        <div v-for="attribute in form.attributes" :key="attribute.id"
                            class="rounded-xl border border-slate-200 bg-white p-4">
                            <FormLabel>{{ attribute.name }}</FormLabel>
                            <MultiSelectInput v-model="form.attributeValues[attribute.id]"
                                :options="attributeValueOptions[attribute.id] || []"
                                placeholder="Select attribute values" multiple label="value" track-by="id"
                                :disabled="loadingAttributeValues">
                                <template v-if="isColorAttribute(attribute)" #option="{ option }">
                                    <div class="flex items-center gap-2">
                                        <span class="h-3.5 w-3.5 rounded-full border border-slate-200"
                                            :style="{ backgroundColor: option.color_code || '#ffffff' }"></span>
                                        <span>{{ option.value }}</span>
                                    </div>
                                </template>
                                <template v-if="isColorAttribute(attribute)" #tag="{ option, remove }">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                                        <span class="h-2.5 w-2.5 rounded-full border border-slate-200"
                                            :style="{ backgroundColor: option.color_code || '#ffffff' }"></span>
                                        {{ option.value }}
                                        <button type="button" class="ml-1 text-slate-400 hover:text-slate-600"
                                            @click="remove(option)">
                                            A-
                                        </button>
                                    </span>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 text-sm font-semibold text-slate-700">Product Price & Stocks</div>
                    <div class="space-y-4">
                        <div>
                            <FormLabel>Unit Price *</FormLabel>
                            <PlainTextInput v-model="form.price" placeholder="Unit price" type="text" />
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <FormLabel>Discount</FormLabel>
                                <PlainTextInput v-model="form.discount" placeholder="Discount value" type="text" />
                            </div>
                            <div>
                                <FormLabel>Discount Type</FormLabel>
                                <SelectInput v-model="form.discountType" :options="discountTypeOptions"
                                    class="mt-2 w-full" />
                            </div>
                        </div>
                        <div v-if="form.hasVariation && form.variations.length"
                            class="rounded-xl border border-slate-200">
                            <div
                                class="grid grid-cols-12 items-center gap-3 border-b border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-500">
                                <div class="col-span-1"></div>
                                <div class="col-span-7">Variant</div>
                                <div class="col-span-4 text-right">Variant Price</div>
                            </div>
                            <div v-for="variation in form.variations" :key="variation.key"
                                class="border-b border-slate-200 last:border-b-0">
                                <div class="grid grid-cols-12 items-center gap-3 px-3 py-2">
                                    <button
                                        class="col-span-1 inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-primary-300 hover:text-primary-600"
                                        type="button" @click="toggleVariation(variation.key)">
                                        <ChevronUp v-if="expandedVariationKey === variation.key" class="h-4 w-4" />
                                        <ChevronDown v-else class="h-4 w-4" />
                                    </button>
                                    <div class="col-span-7 text-sm font-semibold text-slate-700">
                                        {{ variation.label || "Variant" }}
                                    </div>
                                    <div class="col-span-4">
                                        <PlainTextInput v-model="variation.price" placeholder="0.00" type="text" />
                                    </div>
                                </div>
                                <div v-if="expandedVariationKey === variation.key"
                                    class="grid gap-4 border-t border-slate-200 px-3 py-3 md:grid-cols-3">
                                    <div>
                                        <FormLabel>SKU</FormLabel>
                                        <PlainTextInput v-model="variation.sku" placeholder="SKU" type="text" />
                                    </div>
                                    <div>
                                        <FormLabel>Quantity</FormLabel>
                                        <PlainTextInput v-model="variation.quantity" placeholder="0" type="text" />
                                    </div>
                                    <div>
                                        <FormLabel>Photo</FormLabel>
                                        <FileInput v-model="variation.image" accept="image/*" label="Upload image"
                                            compact />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!(form.hasVariation && form.variations.length)">
                            <FormLabel>Quantity *</FormLabel>
                            <PlainTextInput v-model="form.quantity" placeholder="Quantity" type="text" />
                        </div>
                        <div v-if="!(form.hasVariation && form.variations.length)">
                            <FormLabel>SKU</FormLabel>
                            <PlainTextInput v-model="form.sku" placeholder="SKU" type="text" />
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 text-sm font-semibold text-slate-700">Product Description</div>
                    <TextEditor v-model="form.description"
                        class="w-full rounded-xl border border-primary-200 px-4 py-3 text-sm outline-none ring-0 transition focus:border-primary-300 focus:shadow-sm"
                        rows="6" placeholder="Write a detailed product description"></TextEditor>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <Image class="h-4 w-4 text-slate-400" />
                        Product Images
                    </div>
                    <div class="space-y-4">
                        <div>
                            <FormLabel>Product Thumbnail</FormLabel>
                            <FileInput v-model="form.thumbnail" accept="image/*" label="Choose thumbnail" />
                        </div>
                        <div>
                            <FormLabel>Product Gallery Images</FormLabel>
                            <FileInput v-model="form.gallery" accept="image/*" label="Choose gallery images" multiple />
                            <div v-if="galleryPreviews.length" class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div v-for="(preview, index) in galleryPreviews" :key="`${preview.url}-${index}`"
                                    class="relative overflow-hidden rounded-xl border border-slate-200 bg-white">
                                    <button
                                        class="absolute right-2 top-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-slate-500 shadow-sm transition hover:text-rose-600"
                                        type="button" @click="removeGalleryImage(index)">
                                        x
                                    </button>
                                    <div class="flex items-center gap-3 p-3">
                                        <div class="h-16 w-16 overflow-hidden rounded-lg bg-slate-100">
                                            <img v-if="preview.url" :src="preview.url" :alt="preview.name"
                                                class="h-full w-full object-cover" />
                                            <div v-else class="h-full w-full bg-slate-100"></div>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-700">{{ preview.name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 text-sm font-semibold text-slate-700">Product Status</div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3">
                            <FormLabel>Active Status</FormLabel>
                            <Switch v-model="form.statusActive" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <PrimaryButton class="w-auto px-8" :disabled="saving" @click="updateProduct">
                {{ saving ? "Saving..." : "Update Product" }}
            </PrimaryButton>
        </div>
    </section>
</template>
