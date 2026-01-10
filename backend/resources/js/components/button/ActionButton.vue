<script setup>
import { computed } from "vue";

const props = defineProps({
    to: {
        type: [String, Object],
        default: null,
    },
    type: {
        type: String,
        default: "button",
    },
});

const tag = computed(() => (props.to ? "RouterLink" : "button"));
const buttonType = computed(() => (props.to ? undefined : props.type));
</script>

<template>
    <component
        :is="tag"
        :to="to || undefined"
        :type="buttonType"
        v-bind="$attrs"
        class="inline-flex items-center gap-2 rounded-lg bg-primary-500 px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:shadow-md disabled:opacity-60 cursor-pointer"
    >
        <slot />
    </component>
</template>
