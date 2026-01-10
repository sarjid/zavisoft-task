<script setup>
import { reactive, ref } from "vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import axiosInstance from "@/services/axiosService";

const emit = defineEmits(["update:modelValue"]);
const props = defineProps({
    modelValue: {
        type: String,
    },
});

const uploadUrl = ref("/admin/editor-file/upload");
const editor = ref(ClassicEditor);
const editorConfig = reactive({
    extraPlugins: [MyCustomUploadAdapterPlugin],
});

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
}

class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    async upload() {
        const data = new FormData();
        const file = await this.loader.file;
        data.append('upload', file);
        return axiosInstance
            .post(uploadUrl.value, data)
            .then((response) => response.data)
            .then((result) => {
                if (result && result.url) {
                    return {
                        default: result.url,
                    };
                }
                throw new Error("Upload failed");
            });
    }

    abort() {
        // Handle if needed
    }
}


// Emit update:modelValue when content changes
const handleInput = (data) => {
    emit("update:modelValue", data);
};
</script>

<template>
    <ckeditor
        :editor="editor"
        :config="editorConfig"
        :modelValue="modelValue"
        @update:modelValue="handleInput"
        v-bind="$attrs"
    />
</template>
