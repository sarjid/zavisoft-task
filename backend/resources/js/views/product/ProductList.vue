<script setup>
import { reactive, ref, watch } from "vue";
import { useProduct } from "@/stores/product";
import { Switch, PlainTextInput, SelectInput, CheckboxInput, FormLabel, FileInput } from '@/components/form';
import AppTable from "@/components/table/AppTable.vue";
import AppTableCell from "@/components/table/AppTableCell.vue";
import AppTableRow from "@/components/table/AppTableRow.vue";
import { PrimaryButton, ActionButton, ViewButton, EditButton } from "@/components/button";
import AppModal from "@/components/modal/AppModal.vue";

import useFilters from "@/composables/useFilters";
import useBulkSelection from "@/composables/useBulkSelection";
import useToggleStatus from "@/composables/useToggleStatus";
import useNotify from "@/composables/useNotify";
import { TailwindPagination } from "laravel-vue-pagination";
import {
    ChevronDown,
    Image,
    Plus,
    Search,
    Trash2,
} from 'lucide-vue-next';


const productStore = useProduct();
const notify = useNotify();

const productFilters = reactive({
    search: "",
    perPage: 8,
    page: 1,
});

const showCreateModal = ref(false);
const creating = ref(false);
const fileInputKey = ref(0);
const createForm = reactive({
    name: "",
    image: null,
    status: true,
});

const showViewModal = ref(false);
const viewing = ref(false);
const viewProduct = ref(null);

const perPageOptions = [
    { value: 8, label: "8 per page" },
    { value: 10, label: "10 per page" },
    { value: 20, label: "20 per page" },
    { value: 50, label: "50 per page" },
];

const { data: products, pagination, loading, refresh } = useFilters(productFilters, "/admin/products");

const {
    selectedIds,
    selectAllRef,
    allSelected,
    toggleAll,
    resetSelection,
} = useBulkSelection(products);

watch(
    () => [productFilters.search, productFilters.perPage],
    () => {
        productFilters.page = 1;
    }
);

const changePage = (page) => {
    productFilters.page = page;
};

const { toggleStatus } = useToggleStatus((product, nextStatus) => {
    return productStore.status(product.id, nextStatus);
});

const bulkDelete = async () => {
    if (confirm('Are You Shure Want To Delete This')) {

        if (!selectedIds.value.length) return;
        try {
            await productStore.bulkDelete(selectedIds.value);
            notify.success(`${selectedIds.value.length} products deleted`);
            resetSelection();
            await refresh();
        } catch (error) {
            notify.error("Failed to delete products");
        }
    }
};

const resetCreateForm = () => {
    createForm.name = "";
    createForm.image = null;
    createForm.status = true;
    fileInputKey.value += 1;
};

const openCreateModal = () => {
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
    resetCreateForm();
};

const openViewModal = async (product) => {
    viewing.value = true;
    showViewModal.value = true;
    try {
        const data = await productStore.show(product.id);
        viewProduct.value = data?.product ?? null;
    } catch (error) {
        notify.error("Failed to load product");
        showViewModal.value = false;
    } finally {
        viewing.value = false;
    }
};

const closeViewModal = () => {
    showViewModal.value = false;
    viewProduct.value = null;
};

const createproduct = async () => {
    if (!createForm.name.trim()) {
        notify.error("product name is required");
        return;
    }

    creating.value = true;
    try {
        const formData = new FormData();
        formData.append("name", createForm.name.trim());
        formData.append("status", createForm.status ? 1 : 0);
        if (createForm.image) {
            formData.append("image", createForm.image);
        }

        await productStore.store(formData);
        notify.success("product created");
        closeCreateModal();
        await refresh();
    } catch (error) {
        notify.error("Failed to create product");
    } finally {
        creating.value = false;
    }
};

</script>



<template>
    <section class="space-y-6 px-6 py-6">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-semibold text-slate-900">Product products</h2>
            <p class="text-sm text-slate-500">Manage and organize all product groups.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">

                <ActionButton :to="{ name: 'admin-products-create' }">
                    <Plus class="h-4 w-4" />
                    Add New
                </ActionButton>
                <button
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 disabled:opacity-50"
                    type="button" :disabled="!selectedIds.length" @click="bulkDelete">
                    <Trash2 class="h-4 w-4" />
                    Delete Selected
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                        {{ selectedIds.length }}
                    </span>
                </button>

            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <div class="relative flex-1">
                    <Search
                        class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 mt-2" />
                    <PlainTextInput v-model="productFilters.search" placeholder="Search products" type="text"
                        class="pl-10" />
                </div>
                <div class="relative">
                    <SelectInput v-model.number="productFilters.perPage" :options="perPageOptions" />
                    <ChevronDown
                        class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                </div>
            </div>

            <AppTable :loading="loading" :columns="7" :rows="6">
                <template #head>
                    <tr>
                        <th class="px-4 py-3">
                            <CheckboxInput ref="selectAllRef" :model-value="allSelected" @change="toggleAll" />
                        </th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Base Price</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Manage</th>
                    </tr>
                </template>
                <AppTableRow v-for="product in products" :key="product.id">
                    <AppTableCell>
                        <CheckboxInput v-model="selectedIds" :value="product.id" />
                    </AppTableCell>
                    <AppTableCell>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-lg bg-slate-100 text-slate-400">
                                <img v-if="product.thumbnail" :src="product.thumbnail" :alt="product.name"
                                    class="h-full w-full object-cover" />
                                <Image v-else class="h-5 w-5" />
                            </div>
                            <span class="font-semibold text-slate-900">{{ product.name }}</span>
                        </div>
                    </AppTableCell>

                    <AppTableCell>
                        <span class="text-sm font-semibold text-slate-700">
                            {{ product.unit_price ?? 0 }}
                        </span>
                    </AppTableCell>

                    <AppTableCell>
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold"
                            :class="product.variants_count ? 'bg-primary-50 text-primary-700' : 'bg-slate-100 text-slate-600'"
                        >
                            {{ product.variants_count ? "Variant" : "Single" }}
                        </span>
                    </AppTableCell>

                    <AppTableCell>
                        <span
                            class="text-sm font-semibold"
                            :class="(product.current_stock ?? 0) === 0 ? 'text-rose-600' : 'text-slate-700'"
                        >
                            {{ product.current_stock ?? 0 }}
                        </span>
                    </AppTableCell>

                    <AppTableCell>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold"
                                :class="product.status ? 'text-emerald-600' : 'text-rose-600'">
                                {{ product.status ? 'Active' : 'Inactive' }}
                            </span>
                            <Switch :model-value="Boolean(product.status)"
                                @update:modelValue="toggleStatus(product, $event)" />
                        </div>
                    </AppTableCell>
                    <AppTableCell class="text-right">
                        <div class="flex justify-end gap-2">
                            <EditButton as="router-link" :to="{ name: 'admin-products-edit', params: { productId: product.id } }" />
                            <ViewButton type="button" @click="openViewModal(product)" />
                        </div>
                    </AppTableCell>
                </AppTableRow>
            </AppTable>

            <div class="mt-5 overflow-x-auto">
                <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600 min-w-max">
                    <span v-if="pagination?.meta">
                        {{ pagination.meta.from ?? 0 }}-{{ pagination.meta.to ?? 0 }} of
                        {{ pagination.meta.total ?? 0 }}
                    </span>
                    <TailwindPagination v-if="pagination" :data="pagination" @pagination-change-page="changePage" />
                </div>
            </div>
        </div>

        <AppModal v-model="showCreateModal" title="Add product" @close="resetCreateForm">
            <div class="space-y-4">
                <div>
                    <FormLabel>product Name</FormLabel>
                    <PlainTextInput v-model="createForm.name" placeholder="Enter product name" type="text" />
                </div>
                <div>
                    <FormLabel>Image</FormLabel>
                    <FileInput :key="fileInputKey" v-model="createForm.image" accept="image/*" />
                </div>
                <div class="flex items-center justify-between">
                    <FormLabel>Status</FormLabel>
                    <Switch v-model="createForm.status" />
                </div>
            </div>
            <template #footer>
                <PrimaryButton type="button" :disabled="creating" @click="createproduct">
                    {{ creating ? "Creating..." : "Create product" }}
                </PrimaryButton>
            </template>
        </AppModal>

        <AppModal v-model="showViewModal" title="Product Details" maxWidthClass="max-w-5xl" @close="closeViewModal">
            <div v-if="viewing" class="py-6 text-center text-sm font-semibold text-slate-500">
                Loading product...
            </div>
            <div v-else-if="viewProduct" class="max-h-[70vh] space-y-6 overflow-y-auto">
                <div class="flex items-start gap-4">
                    <div class="h-20 w-20 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                        <img
                            v-if="viewProduct.thumbnail"
                            :src="viewProduct.thumbnail"
                            :alt="viewProduct.name"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="flex h-full w-full items-center justify-center text-slate-400">IMG</div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-slate-900">{{ viewProduct.name }}</div>
                        <div class="mt-1 text-sm text-slate-500">{{ viewProduct.slug }}</div>
                        <div class="mt-2 flex flex-wrap gap-2 text-xs font-semibold text-slate-600">
                            <span class="rounded-full bg-slate-100 px-2 py-1">
                                {{ viewProduct.has_variant ? "Variant" : "Single" }}
                            </span>
                            <span class="rounded-full bg-primary-50 px-2 py-1 text-primary-700">
                                {{ viewProduct.status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">Unit Price</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700">{{ viewProduct.unit_price ?? 0 }}</div>
                    </div>
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">Discount</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700">
                            {{ viewProduct.discount ?? 0 }}
                            <span v-if="viewProduct.discount_type" class="text-xs text-slate-500">
                                ({{ viewProduct.discount_type }})
                            </span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-primary-100 bg-primary-50 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">Current Price</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700">{{ viewProduct.current_price ?? 0 }}</div>
                    </div>
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">Stock</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700">{{ viewProduct.stock ?? 0 }}</div>
                    </div>
                </div>

                <div
                    v-if="viewProduct.description"
                    class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-600"
                    v-html="viewProduct.description"
                >
                </div>

                <div v-if="viewProduct.images?.length" class="space-y-2">
                    <div class="text-sm font-semibold text-slate-700">Gallery</div>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                        <div
                            v-for="(image, index) in viewProduct.images"
                            :key="`${image}-${index}`"
                            class="h-24 overflow-hidden rounded-lg border border-slate-200 bg-slate-100"
                        >
                            <img :src="image" :alt="`${viewProduct.name} ${index + 1}`" class="h-full w-full object-cover" />
                        </div>
                    </div>
                </div>

                <div v-if="viewProduct.variants?.length" class="space-y-3">
                    <div class="text-sm font-semibold text-slate-700">Variants</div>
                    <div class="space-y-2">
                        <div
                            v-for="variant in viewProduct.variants"
                            :key="variant.id"
                            class="rounded-xl border border-slate-200 px-4 py-3"
                        >
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                                        <img
                                            v-if="variant.image"
                                            :src="variant.image"
                                            :alt="variant.sku || 'Variant image'"
                                            class="h-full w-full object-cover"
                                        />
                                        <div v-else class="flex h-full w-full items-center justify-center text-xs text-slate-400">IMG</div>
                                    </div>
                                    <div class="text-sm font-semibold text-slate-700">
                                        {{ variant.attributes?.map((attr) => `${attr.attribute_name}: ${attr.attribute_value}`).join(" / ") }}
                                    </div>
                                </div>
                                <div class="text-xs font-semibold text-slate-500">
                                    SKU: {{ variant.sku || "-" }}
                                </div>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-3 text-xs font-semibold text-slate-600">
                                <span class="rounded-full bg-slate-100 px-2 py-1">Price: {{ variant.price ?? 0 }}</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1">Qty: {{ variant.quantity ?? 0 }}</span>
                                <span
                                    class="rounded-full px-2 py-1"
                                    :class="variant.stock_status?.toLowerCase() === 'out of stock'
                                        ? 'bg-rose-100 text-rose-600'
                                        : 'bg-emerald-100 text-emerald-600'"
                                >
                                    {{ variant.stock_status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppModal>
    </section>
</template>
