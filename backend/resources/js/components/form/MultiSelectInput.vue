<script setup>
import Multiselect from "vue-multiselect/dist/vue-multiselect.esm.js";
import "vue-multiselect/dist/vue-multiselect.css";

defineEmits(["update:modelValue"]);

defineProps({
    modelValue: {
        type: [Array, Object, String, Number, null],
        default: null,
    },
    options: {
        type: Array,
        default: () => [],
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        default: "Select option",
    },
    trackBy: {
        type: String,
        default: "value",
    },
    label: {
        type: String,
        default: "label",
    },
});
</script>

<template>
    <Multiselect
        class="mt-2"
        :model-value="modelValue"
        :options="options"
        :multiple="multiple"
        :placeholder="placeholder"
        :track-by="trackBy"
        :label="label"
        :close-on-select="!multiple"
        :clear-on-select="!multiple"
        :preserve-search="true"
        v-bind="$attrs"
        @update:modelValue="$emit('update:modelValue', $event)"
    >
        <template #option="slotProps">
            <slot name="option" v-bind="slotProps">
                <span>{{ slotProps.option?.[label] ?? slotProps.option }}</span>
            </slot>
        </template>
        <template #tag="slotProps">
            <slot name="tag" v-bind="slotProps">
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">
                    {{ slotProps.option?.[label] ?? slotProps.option }}
                    <button
                        type="button"
                        class="ml-1 text-slate-400 hover:text-slate-600"
                        @click="slotProps.remove(slotProps.option)"
                    >
                        x
                    </button>
                </span>
            </slot>
        </template>
    </Multiselect>
</template>
