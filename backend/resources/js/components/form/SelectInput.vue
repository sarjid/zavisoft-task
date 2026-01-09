<script setup>
const emit = defineEmits(["update:modelValue"]);
const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    modelModifiers: {
        type: Object,
        default: () => ({}),
    },
    options: {
        type: Array,
        default: () => [],
    },
});

const onChange = (event) => {
    let value = event.target.value;
    if (props.modelModifiers.number) {
        value = Number(value);
    }
    emit("update:modelValue", value);
};
</script>

<template>
    <select
        class="appearance-none rounded-lg border border-primary-200 bg-white px-4 py-2 pr-9 text-sm font-semibold text-slate-600"
        :value="modelValue"
        @change="onChange"
        v-bind="$attrs"
    >
        <option
            v-for="option in options"
            :key="option.value"
            :value="option.value"
        >
            {{ option.label }}
        </option>
    </select>
</template>
