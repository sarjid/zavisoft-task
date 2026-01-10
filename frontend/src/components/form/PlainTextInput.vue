<script setup>
defineEmits(["update:modelValue"]);
const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <input
    class="mt-2 w-full rounded-xl border border-primary-200 px-4 py-3 text-sm outline-none ring-0 transition focus:border-primary-300 focus:shadow-sm"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    v-bind="$attrs" />
</template>
