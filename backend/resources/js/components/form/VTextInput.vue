<script setup>
import { Field } from "vee-validate";

defineEmits(["update:modelValue"]);
defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    name: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Field :name="name" v-slot="{ errors, field, meta }">
        <input
            class="mt-2 w-full rounded-xl border px-4 py-3 text-sm outline-none ring-0 transition focus:shadow-sm"
            :class="{
                'border-primary-200 focus:border-primary-300': !(meta.touched && errors.length),
                'border-red-400 focus:border-red-500': meta.touched && errors.length,
            }"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            v-bind="{ ...$attrs, ...field }"
        />
        <span class="mt-1 block text-sm text-red-500" v-show="errors.length">
            {{ errors[0] }}
        </span>
    </Field>
</template>
