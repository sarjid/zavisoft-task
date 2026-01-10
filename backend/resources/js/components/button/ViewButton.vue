<script setup>
import { Eye } from "lucide-vue-next";
</script>

<template>
    <button
        type="button"
        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-500 text-white transition hover:bg-primary-600"
        v-bind="$attrs"
    >
        <Eye class="h-4 w-4" />
        <slot />
    </button>
</template>
