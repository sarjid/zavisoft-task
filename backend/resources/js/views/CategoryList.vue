<script setup>
import { computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useCategory } from "@/stores/category";
import { Switch } from '@/components/form';
import {
    ArrowUpDown,
    ChevronDown,
    ChevronRight,
    Image,
    Pencil,
    Plus,
    Search,
    Trash2,
} from 'lucide-vue-next';




const categoryStore = useCategory();

const router = useRouter();
onMounted(() => {
    fetchCategories();
})
const fetchCategories = async () => {
    await categoryStore.index();
};

const categories = computed(() => categoryStore.getData);
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

            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <div class="relative flex-1">
                    <Search
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-primary-500 focus:outline-none"
                        placeholder="Search categories" type="text" />
                </div>
                <button
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600"
                    type="button">
                    8 Per Page
                    <ChevronDown class="h-4 w-4" />
                </button>
            </div>

            <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">

                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3">
                                <input class="h-4 w-4 rounded border-slate-300 text-primary-600" type="checkbox" />
                            </th>
                            <th class="px-4 py-3">Categories</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600">
                        <tr v-for="category in categoryStore.getCategories" :key="category.id"
                            class="border-t border-slate-100">
                            <td class="px-4 py-3">
                                <input class="h-4 w-4 rounded border-slate-300 text-primary-600" type="checkbox" />
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

                                <Switch :checked="category.status" :value="category.status ? 0 : 1"
                                    @change="changeStatus(category, $event.target.value)" />



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
                    </tbody>
                </table>
            </div>

            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600">
                <span>8 of 9</span>
                <div class="flex items-center gap-2">
                    <button class="h-8 w-8 rounded-full border border-slate-200" type="button">
                        1
                    </button>
                    <button class="h-8 w-8 rounded-full bg-primary-500 text-white" type="button">
                        2
                    </button>
                    <button class="h-8 w-8 rounded-full border border-slate-200" type="button">
                        3
                    </button>
                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200"
                        type="button">
                        <ChevronRight class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>
