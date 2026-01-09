<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    modelValue: {
        type: [Boolean, Array],
        default: false,
    },
    value: {
        type: [String, Number, Boolean, Object],
        default: undefined,
    },
    name: {
        type: String,
        default: undefined,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue", "change"]);

const inputRef = ref(null);
defineExpose({ input: inputRef });

const isChecked = computed(() => {
    if (Array.isArray(props.modelValue)) {
        return props.modelValue.includes(props.value);
    }

    return Boolean(props.modelValue);
});

const onChange = (event) => {
    let nextValue;

    if (Array.isArray(props.modelValue)) {
        const updated = new Set(props.modelValue);
        if (event.target.checked) {
            updated.add(props.value);
        } else {
            updated.delete(props.value);
        }
        nextValue = Array.from(updated);
    } else {
        nextValue = event.target.checked;
    }

    emit("update:modelValue", nextValue);
    emit("change", event);
};
</script>

<template>
    <input
        ref="inputRef"
        type="checkbox"
        :name="name"
        :value="value"
        :checked="isChecked"
        :disabled="disabled"
        v-bind="$attrs"
        class="h-4 w-4 rounded border-slate-300 text-primary-600 accent-primary-600 transition focus:ring-2 focus:ring-primary-500/30 focus:ring-offset-1 disabled:opacity-60"
        @change="onChange" />
</template>
