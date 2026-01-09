<script setup>
import { computed } from "vue";
import { RouterLink } from "vue-router";
import { Pencil } from "lucide-vue-next";

const props = defineProps({
    as: {
        type: String,
        default: "button",
    },
    to: {
        type: [String, Object],
        default: null,
    },
    type: {
        type: String,
        default: "button",
    },
});

const componentTag = computed(() => (props.as === "router-link" ? RouterLink : "button"));
const resolvedTo = computed(() => (props.as === "router-link" ? props.to : undefined));
const resolvedType = computed(() => (props.as === "button" ? props.type : undefined));
</script>

<template>
    <component
        :is="componentTag"
        v-bind="$attrs"
        :to="resolvedTo"
        :type="resolvedType"
        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-white">
        <Pencil class="h-4 w-4" />
        <slot />
    </component>
</template>
