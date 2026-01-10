<script setup>
import { reactive, ref, watch } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import { Eye, Search, ShoppingBag } from "lucide-vue-next";
import { PlainTextInput, SelectInput } from "@/components/form";
import AppTable from "@/components/table/AppTable.vue";
import AppTableCell from "@/components/table/AppTableCell.vue";
import AppTableRow from "@/components/table/AppTableRow.vue";
import AppModal from "@/components/modal/AppModal.vue";
import axiosInstance from "@/services/AxiosService";
import useFilters from "@/composables/useFilters";
import useNotify from "@/composables/useNotify";

const notify = useNotify();

const orderFilters = reactive({
    search: "",
    perPage: 10,
    page: 1,
});

const perPageOptions = [
    { value: 10, label: "10 per page" },
    { value: 20, label: "20 per page" },
    { value: 50, label: "50 per page" },
];

const { data: orders, pagination, loading, refresh } = useFilters(orderFilters, "/admin/orders");

watch(
    () => [orderFilters.search, orderFilters.perPage],
    () => {
        orderFilters.page = 1;
    }
);

const changePage = (page) => {
    orderFilters.page = page;
};

const showViewModal = ref(false);
const viewing = ref(false);
const viewOrder = ref(null);

const openViewModal = async (order) => {
    viewing.value = true;
    showViewModal.value = true;
    try {
        const res = await axiosInstance.get(`/admin/orders/${order.id}`);
        viewOrder.value = res?.data?.data?.order ?? null;
    } catch (error) {
        notify.error("Failed to load order");
        showViewModal.value = false;
    } finally {
        viewing.value = false;
    }
};

const closeViewModal = () => {
    showViewModal.value = false;
    viewOrder.value = null;
};
</script>

<template>
    <section class="space-y-6 px-6 py-6">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-semibold text-slate-900">Orders</h2>
            <p class="text-sm text-slate-500">Review recent customer orders and items.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[220px]">
                    <Search class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 mt-2" />
                    <PlainTextInput v-model="orderFilters.search" placeholder="Search by name, phone, or ID" type="text" class="pl-10" />
                </div>
                <div class="relative">
                    <SelectInput v-model.number="orderFilters.perPage" :options="perPageOptions" />
                </div>
            </div>

            <AppTable :loading="loading" :columns="6" :rows="6">
                <template #head>
                    <tr>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3 text-right">Manage</th>
                    </tr>
                </template>
                <AppTableRow v-for="order in orders" :key="order.id">
                    <AppTableCell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                                <ShoppingBag class="h-4 w-4" />
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">#{{ order.id }}</div>
                                <div class="text-xs text-slate-500">{{ order.created_at }}</div>
                            </div>
                        </div>
                    </AppTableCell>
                    <AppTableCell>
                        <span class="font-semibold text-slate-700">{{ order.customer_name }}</span>
                    </AppTableCell>
                    <AppTableCell>
                        <span class="text-sm text-slate-600">{{ order.phone }}</span>
                    </AppTableCell>
                    <AppTableCell>
                        <span class="text-sm font-semibold text-slate-700">{{ order.items_count ?? 0 }}</span>
                    </AppTableCell>
                    <AppTableCell>
                        <span class="text-sm font-semibold text-slate-700">{{ order.total ?? 0 }}</span>
                    </AppTableCell>
                    <AppTableCell class="text-right">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                            @click="openViewModal(order)">
                            <Eye class="h-4 w-4" />
                            View
                        </button>
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

        <AppModal v-model="showViewModal" title="Order Details" maxWidthClass="max-w-4xl" @close="closeViewModal">
            <div v-if="viewing" class="py-6 text-center text-sm font-semibold text-slate-500">
                Loading order...
            </div>
            <div v-else-if="viewOrder" class="space-y-6">
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">Customer</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700">{{ viewOrder.customer_name }}</div>
                        <div class="text-xs text-slate-500">{{ viewOrder.phone }}</div>
                    </div>
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">Address</div>
                        <div class="mt-1 text-sm text-slate-700">{{ viewOrder.address }}</div>
                    </div>
                    <div class="rounded-xl border border-slate-200 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">Total</div>
                        <div class="mt-1 text-sm font-semibold text-slate-700">{{ viewOrder.total }}</div>
                        <div class="text-xs text-slate-500">Status: {{ viewOrder.status }}</div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200">
                    <div class="border-b border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">Items</div>
                    <div class="divide-y divide-slate-200">
                        <div v-for="item in viewOrder.items" :key="item.id" class="px-4 py-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="text-sm font-semibold text-slate-700">{{ item.product_name }}</div>
                                <div class="text-xs text-slate-500">SKU: {{ item.sku || '-' }}</div>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-3 text-xs font-semibold text-slate-600">
                                <span class="rounded-full bg-slate-100 px-2 py-1">Qty: {{ item.quantity }}</span>
                                <span class="rounded-full bg-slate-100 px-2 py-1">Unit: {{ item.unit_price }}</span>
                                <span class="rounded-full bg-primary-50 px-2 py-1 text-primary-700">Total: {{ item.total }}</span>
                            </div>
                            <div v-if="item.options?.length" class="mt-2 flex flex-wrap gap-2 text-xs text-slate-500">
                                <span
                                    v-for="option in item.options"
                                    :key="`${item.id}-${option.name}`"
                                    class="rounded-full bg-slate-50 px-2 py-1">
                                    {{ option.name }}: {{ option.value }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="viewOrder.notes" class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-600">
                    {{ viewOrder.notes }}
                </div>
            </div>
        </AppModal>
    </section>
</template>
