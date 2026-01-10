<script setup>
import { computed, onUnmounted, ref, watch } from "vue";

const emit = defineEmits(["update:modelValue", "change"]);
const props = defineProps({
    modelValue: {
        type: [File, Array, Object, String, null],
        default: null,
    },
    label: {
        type: String,
        default: "Choose file",
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    compact: {
        type: Boolean,
        default: false,
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
    if (Array.isArray(props.modelValue)) {
        const firstImage = props.modelValue.find(
            (file) => file instanceof File && file.type?.startsWith("image/")
        );
        if (firstImage) {
            return objectUrl.value;
        }
    }
    return "";
});

const isImage = computed(() => Boolean(previewUrl.value));
const fileName = computed(() => {
    if (props.modelValue instanceof File) {
        return props.modelValue.name;
    }
    if (Array.isArray(props.modelValue)) {
        const count = props.modelValue.filter(
            (item) => item instanceof File || typeof item === "string"
        ).length;
        return count ? `${count} file${count > 1 ? "s" : ""} selected` : "";
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
    const files = event.target.files ? Array.from(event.target.files) : [];
    const value = props.multiple ? files : files[0] ?? null;
    emit("update:modelValue", value);
    emit("change", event);
};

watch(
    () => props.modelValue,
    (nextValue) => {
        cleanupObjectUrl();
        if (nextValue instanceof File && nextValue.type?.startsWith("image/")) {
            objectUrl.value = URL.createObjectURL(nextValue);
        }
        if (Array.isArray(nextValue)) {
            const firstImage = nextValue.find(
                (file) => file instanceof File && file.type?.startsWith("image/")
            );
            if (firstImage) {
                objectUrl.value = URL.createObjectURL(firstImage);
            }
        }
    }
);

onUnmounted(() => {
    cleanupObjectUrl();
});
</script>

<template>
    <label class="group block w-full cursor-pointer">
        <input class="sr-only" type="file" :multiple="multiple" @change="onChange" v-bind="$attrs" />
        <div
            class="flex items-center gap-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-sm text-slate-600 transition group-hover:border-primary-300 group-hover:bg-primary-50"
            :class="compact ? 'px-3 py-3' : 'px-4 py-4'"
        >
            <div
                class="flex items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-white"
                :class="compact ? 'h-12 w-12' : 'h-16 w-16'"
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
                class="rounded-full border border-slate-200 bg-white text-xs font-semibold text-slate-600 transition group-hover:border-primary-300 group-hover:text-primary-600"
                :class="compact ? 'px-2.5 py-1' : 'px-3 py-1'"
            >
                Browse
            </div>
        </div>
    </label>
</template>
