<script setup>
const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <button class="relative inline-flex h-6 w-11 items-center rounded-full px-1 transition cursor-pointer"
        :class="modelValue ? 'bg-primary-500' : 'bg-slate-200'" type="button" role="switch" :aria-checked="modelValue"
        @click="emit('update:modelValue', !modelValue)" v-bind="$attrs">
        <span class="h-4 w-4 rounded-full bg-white shadow-sm transition"
            :class="modelValue ? 'translate-x-5' : 'translate-x-0'"></span>
    </button>
</template>
