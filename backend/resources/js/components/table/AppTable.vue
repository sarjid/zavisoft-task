<script setup>
import TableSkeleton from "@/components/skeleton/TableSkeleton.vue";

defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    columns: {
        type: Number,
        default: 4,
    },
    rows: {
        type: Number,
        default: 6,
    },
});
</script>

<template>
    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="min-w-[720px] w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <slot name="head" />
            </thead>
            <TableSkeleton v-if="loading" :columns="columns" :rows="rows" />
            <tbody v-else class="text-slate-600">
                <slot />
            </tbody>
        </table>
    </div>
</template>
