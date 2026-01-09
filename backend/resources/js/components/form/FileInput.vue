<script setup>
import { computed, onUnmounted, ref, watch } from "vue";

const emit = defineEmits(["update:modelValue", "change"]);
const props = defineProps({
    modelValue: {
        type: [File, Object, String, null],
        default: null,
    },
    label: {
        type: String,
        default: "Choose file",
    },
});

const objectUrl = ref("");
const previewUrl = computed(() => {
    if (typeof props.modelValue === "string") {
        return props.modelValue;
    }
    if (props.modelValue instanceof File && props.modelValue.type?.startsWith("image/")) {
        return objectUrl.value;
    }
    return "";
});

const isImage = computed(() => Boolean(previewUrl.value));
const fileName = computed(() => {
    if (props.modelValue instanceof File) {
        return props.modelValue.name;
    }
    return "";
});

const cleanupObjectUrl = () => {
    if (objectUrl.value) {
        URL.revokeObjectURL(objectUrl.value);
        objectUrl.value = "";
    }
};

const onChange = (event) => {
    const file = event.target.files?.[0] ?? null;
    emit("update:modelValue", file);
    emit("change", event);
};

watch(
    () => props.modelValue,
    (nextValue) => {
        cleanupObjectUrl();
        if (nextValue instanceof File && nextValue.type?.startsWith("image/")) {
            objectUrl.value = URL.createObjectURL(nextValue);
        }
    }
);

onUnmounted(() => {
    cleanupObjectUrl();
});
</script>

<template>
    <label class="group block w-full cursor-pointer">
        <input class="sr-only" type="file" @change="onChange" v-bind="$attrs" />
        <div
            class="flex items-center gap-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 transition group-hover:border-primary-300 group-hover:bg-primary-50"
        >
            <div
                class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-white"
            >
                <img
                    v-if="isImage"
                    :src="previewUrl"
                    alt="Preview"
                    class="h-full w-full object-cover"
                />
                <span v-else class="text-xs font-semibold text-slate-400">FILE</span>
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-slate-800">{{ label }}</div>
                <div class="mt-1 text-xs text-slate-500">
                    {{ fileName || "PNG, JPG or WEBP (max 5MB)" }}
                </div>
            </div>
            <div
                class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600 transition group-hover:border-primary-300 group-hover:text-primary-600"
            >
                Browse
            </div>
        </div>
    </label>
</template>
