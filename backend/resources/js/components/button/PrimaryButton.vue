<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>


<template>
    <button :type="$type" v-bind="$attrs"
        class="w-full rounded-xl bg-primary-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md disabled:opacity-60 cursor-pointer">
        <slot />
    </button>
</template>
