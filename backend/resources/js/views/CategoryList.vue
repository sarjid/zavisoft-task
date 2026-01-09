<script setup>
import { reactive, watch } from "vue";
import { useCategory } from "@/stores/category";
import { Switch, PlainTextInput, SelectInput } from '@/components/form';
import AppTable from "@/components/table/AppTable.vue";
import useFilters from "@/composables/useFilters";
import useBulkSelection from "@/composables/useBulkSelection";
import useToggleStatus from "@/composables/useToggleStatus";
import useNotify from "@/composables/useNotify";
import { TailwindPagination } from "laravel-vue-pagination";
import {
    ChevronDown,
    Image,
    Pencil,
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
    if (!selectedIds.value.length) return;
    try {
        await categoryStore.bulkDelete(selectedIds.value);
        notify.success(`${selectedIds.value.length} categories deleted`);
        resetSelection();
        await refresh();
    } catch (error) {
        notify.error("Failed to delete categories");
        // keep selection for retry
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
                    type="button">
                    <Plus class="h-4 w-4" />
                    Add New
                </button>
                <button
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 disabled:opacity-50"
                    type="button"
                    :disabled="!selectedIds.length"
                    @click="bulkDelete"
                >
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
                            <input
                                ref="selectAllRef"
                                class="h-4 w-4 rounded border-slate-300 text-primary-600"
                                type="checkbox"
                                :checked="allSelected"
                                @change="toggleAll"
                            />
                        </th>
                        <th class="px-4 py-3">Categories</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Manage</th>
                    </tr>
                </template>
                <tr v-for="category in categories" :key="category.id" class="border-t border-slate-100">
                    <td class="px-4 py-3">
                        <input
                            v-model="selectedIds"
                            :value="category.id"
                            class="h-4 w-4 rounded border-slate-300 text-primary-600"
                            type="checkbox"
                        />
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-lg bg-slate-100 text-slate-400">
                                <img v-if="category.image" :src="category.image" :alt="category.name"
                                    class="h-full w-full object-cover" />
                                <Image v-else class="h-5 w-5" />
                            </div>
                            <span class="font-semibold text-slate-900">{{ category.name }}</span>
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold"
                                :class="category.status ? 'text-emerald-600' : 'text-rose-600'">
                                {{ category.status ? 'Active' : 'Inactive' }}
                            </span>
                            <Switch :model-value="Boolean(category.status)"
                                @update:modelValue="toggleStatus(category, $event)" />
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <button
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-white"
                                type="button">
                                <Pencil class="h-4 w-4" />
                            </button>
                            <button
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500 text-white"
                                type="button">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </td>
                </tr>
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
    </section>
</template>
