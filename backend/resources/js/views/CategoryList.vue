<script setup>
import { reactive, ref, watch } from "vue";
import { useCategory } from "@/stores/category";
import { Switch, PlainTextInput, SelectInput, CheckboxInput, FormLabel, FileInput } from '@/components/form';
import AppTable from "@/components/table/AppTable.vue";
import AppTableCell from "@/components/table/AppTableCell.vue";
import AppTableRow from "@/components/table/AppTableRow.vue";
import { EditButton, DeleteButton, PrimaryButton } from "@/components/button";
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


const categoryStore = useCategory();
const notify = useNotify();

const categoryFilters = reactive({
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

const showEditModal = ref(false);
const updating = ref(false);
const editFileInputKey = ref(0);
const editForm = reactive({
    id: null,
    name: "",
    image: null,
    status: true,
});

const perPageOptions = [
    { value: 8, label: "8 per page" },
    { value: 10, label: "10 per page" },
    { value: 20, label: "20 per page" },
    { value: 50, label: "50 per page" },
];

const { data: categories, pagination, loading, refresh } = useFilters(categoryFilters, "/admin/category");

const {
    selectedIds,
    selectAllRef,
    allSelected,
    toggleAll,
    resetSelection,
} = useBulkSelection(categories);

watch(
    () => [categoryFilters.search, categoryFilters.perPage],
    () => {
        categoryFilters.page = 1;
    }
);

const changePage = (page) => {
    categoryFilters.page = page;
};

const { toggleStatus } = useToggleStatus((category, nextStatus) => {
    return categoryStore.status(category.id, nextStatus);
});

const bulkDelete = async () => {
    if (confirm('Are You Shure Want To Delete This')) {

        if (!selectedIds.value.length) return;
        try {
            await categoryStore.bulkDelete(selectedIds.value);
            notify.success(`${selectedIds.value.length} categories deleted`);
            resetSelection();
            await refresh();
        } catch (error) {
            notify.error("Failed to delete categories");
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

const resetEditForm = () => {
    editForm.id = null;
    editForm.name = "";
    editForm.image = null;
    editForm.status = true;
    editFileInputKey.value += 1;
};

const openEditModal = (category) => {
    editForm.id = category.id;
    editForm.name = category.name;
    editForm.image = category.image || null;
    editForm.status = Boolean(category.status);
    editFileInputKey.value += 1;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    resetEditForm();
};

const createCategory = async () => {
    if (!createForm.name.trim()) {
        notify.error("Category name is required");
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

        await categoryStore.store(formData);
        notify.success("Category created");
        closeCreateModal();
        await refresh();
    } catch (error) {
        notify.error("Failed to create category");
    } finally {
        creating.value = false;
    }
};

const updateCategory = async () => {
    if (!editForm.name.trim()) {
        notify.error("Category name is required");
        return;
    }

    updating.value = true;
    try {
        const formData = new FormData();
        formData.append("name", editForm.name.trim());
        formData.append("status", editForm.status ? 1 : 0);
        if (editForm.image instanceof File) {
            formData.append("image", editForm.image);
        }

        await categoryStore.update(editForm.id, formData);
        notify.success("Category updated");
        closeEditModal();
        await refresh();
    } catch (error) {
        notify.error("Failed to update category");
    } finally {
        updating.value = false;
    }
};
</script>



<template>
    <section class="space-y-6 px-6 py-6">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-semibold text-slate-900">Product Categories</h2>
            <p class="text-sm text-slate-500">Manage and organize all category groups.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <button
                    class="inline-flex items-center gap-2 rounded-lg bg-primary-500 px-4 py-2 text-sm font-semibold text-white"
                    type="button" @click="openCreateModal">
                    <Plus class="h-4 w-4" />
                    Add New
                </button>
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
                    <PlainTextInput v-model="categoryFilters.search" placeholder="Search categories" type="text"
                        class="pl-10" />
                </div>
                <div class="relative">
                    <SelectInput v-model.number="categoryFilters.perPage" :options="perPageOptions" />
                    <ChevronDown
                        class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                </div>
            </div>

            <AppTable :loading="loading" :columns="4" :rows="6">
                <template #head>
                    <tr>
                        <th class="px-4 py-3">
                            <CheckboxInput ref="selectAllRef" :model-value="allSelected" @change="toggleAll" />
                        </th>
                        <th class="px-4 py-3">Categories</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Manage</th>
                    </tr>
                </template>
                <AppTableRow v-for="category in categories" :key="category.id">
                    <AppTableCell>
                        <CheckboxInput v-model="selectedIds" :value="category.id" />
                    </AppTableCell>
                    <AppTableCell>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-lg bg-slate-100 text-slate-400">
                                <img v-if="category.image" :src="category.image" :alt="category.name"
                                    class="h-full w-full object-cover" />
                                <Image v-else class="h-5 w-5" />
                            </div>
                            <span class="font-semibold text-slate-900">{{ category.name }}</span>
                        </div>
                    </AppTableCell>

                    <AppTableCell>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold"
                                :class="category.status ? 'text-emerald-600' : 'text-rose-600'">
                                {{ category.status ? 'Active' : 'Inactive' }}
                            </span>
                            <Switch :model-value="Boolean(category.status)"
                                @update:modelValue="toggleStatus(category, $event)" />
                        </div>
                    </AppTableCell>
                    <AppTableCell class="text-right">
                        <div class="flex justify-end gap-2">
                            <EditButton type="button" @click="openEditModal(category)" />
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

        <AppModal v-model="showCreateModal" title="Add Category" @close="resetCreateForm">
            <div class="space-y-4">
                <div>
                    <FormLabel>Category Name</FormLabel>
                    <PlainTextInput v-model="createForm.name" placeholder="Enter category name" type="text" />
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
                <PrimaryButton type="button" :disabled="creating" @click="createCategory">
                    {{ creating ? "Creating..." : "Create Category" }}
                </PrimaryButton>
            </template>
        </AppModal>

        <AppModal v-model="showEditModal" title="Edit Category" @close="resetEditForm">
            <div class="space-y-4">
                <div>
                    <FormLabel>Category Name</FormLabel>
                    <PlainTextInput v-model="editForm.name" placeholder="Enter category name" type="text" />
                </div>
                <div>
                    <FormLabel>Image</FormLabel>
                    <FileInput :key="editFileInputKey" v-model="editForm.image" accept="image/*" label="Change image" />
                </div>
                <div class="flex items-center justify-between">
                    <FormLabel>Status</FormLabel>
                    <Switch v-model="editForm.status" />
                </div>
            </div>
            <template #footer>
                <PrimaryButton type="button" :disabled="updating" @click="updateCategory">
                    {{ updating ? "Updating..." : "Update Category" }}
                </PrimaryButton>
            </template>
        </AppModal>
    </section>
</template>
