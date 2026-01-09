<script setup>
import { onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: "",
    },
    maxWidthClass: {
        type: String,
        default: "max-w-xl",
    },
});

const emit = defineEmits(["update:modelValue", "close"]);

const close = () => {
    emit("update:modelValue", false);
    emit("close");
};

const onKeydown = (event) => {
    if (event.key === "Escape" && props.modelValue) {
        close();
    }
};

onMounted(() => {
    document.addEventListener("keydown", onKeydown);
});

onUnmounted(() => {
    document.removeEventListener("keydown", onKeydown);
});

watch(
    () => props.modelValue,
    (isOpen) => {
        document.body.classList.toggle("overflow-hidden", isOpen);
    }
);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="modelValue"
                class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8"
                aria-modal="true"
                role="dialog"
            >
                <div class="absolute inset-0 bg-slate-900/50" @click="close"></div>
                <div
                    class="relative w-full rounded-2xl bg-white shadow-xl"
                    :class="maxWidthClass"
                >
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                        <div class="text-lg font-semibold text-slate-900">
                            <slot name="title">
                                {{ title }}
                            </slot>
                        </div>
                        <button
                            type="button"
                            class="rounded-lg px-2 py-1 text-sm font-semibold text-slate-500 hover:text-slate-900"
                            @click="close"
                        >
                            X
                        </button>
                    </div>
                    <div class="px-6 py-5">
                        <slot />
                    </div>
                    <div v-if="$slots.footer" class="border-t border-slate-200 px-6 py-4">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
