<script setup>
defineProps({
    rows: {
        type: Number,
        default: 6,
    },
    columns: {
        type: Number,
        default: 4,
    },
});
</script>

<template>
    <tbody class="animate-pulse">
        <tr v-for="row in rows" :key="row" class="border-t border-slate-100">
            <td v-for="col in columns" :key="col" class="px-4 py-3">
                <div class="h-4 w-full rounded bg-slate-200"></div>
            </td>
        </tr>
    </tbody>
</template>
