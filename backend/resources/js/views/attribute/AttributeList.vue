<script setup>
import { computed, reactive, ref, watch } from "vue";
import { Plus, Search, SlidersHorizontal } from "lucide-vue-next";
import { PlainTextInput, SelectInput, FormLabel } from "@/components/form";
import AppTable from "@/components/table/AppTable.vue";
import AppTableCell from "@/components/table/AppTableCell.vue";
import AppTableRow from "@/components/table/AppTableRow.vue";
import AppModal from "@/components/modal/AppModal.vue";
import { PrimaryButton, EditButton, ActionButton } from "@/components/button";
import { TailwindPagination } from "laravel-vue-pagination";
import axiosInstance from "@/services/AxiosService";
import useFilters from "@/composables/useFilters";
import useNotify from "@/composables/useNotify";

const notify = useNotify();

const attributeFilters = reactive({
    search: "",
    perPage: 10,
    page: 1,
    include_values: 1,
});

const perPageOptions = [
    { value: 10, label: "10 per page" },
    { value: 20, label: "20 per page" },
    { value: 50, label: "50 per page" },
];

const { data: attributes, pagination, loading, refresh } = useFilters(
    attributeFilters,
    "/admin/attributes"
);

watch(
    () => [attributeFilters.search, attributeFilters.perPage],
    () => {
        attributeFilters.page = 1;
    }
);

const changePage = (page) => {
    attributeFilters.page = page;
};

const showCreateModal = ref(false);
const creating = ref(false);
const createForm = reactive({
    name: "",
});

const showEditModal = ref(false);
const updating = ref(false);
const editForm = reactive({
    id: null,
    name: "",
});

const showValuesModal = ref(false);
const currentAttribute = ref(null);

const valueForm = reactive({
    id: null,
    attribute_id: null,
    value: "",
    color_code: "",
});
const savingValue = ref(false);

const attributeOptions = computed(() =>
    attributes.value.map((item) => ({ value: item.id, label: item.name }))
);

const openCreateModal = () => {
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
    createForm.name = "";
};

const openEditModal = (attribute) => {
    editForm.id = attribute.id;
    editForm.name = attribute.name;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.id = null;
    editForm.name = "";
};

const createAttribute = async () => {
    if (!createForm.name.trim()) {
        notify.error("Attribute name is required");
        return;
    }

    creating.value = true;
    try {
        await axiosInstance.post("/admin/attributes", {
            name: createForm.name.trim(),
        });
        notify.success("Attribute created");
        closeCreateModal();
        await refresh();
    } catch (error) {
        notify.error("Failed to create attribute");
    } finally {
        creating.value = false;
    }
};

const updateAttribute = async () => {
    if (!editForm.name.trim()) {
        notify.error("Attribute name is required");
        return;
    }

    updating.value = true;
    try {
        await axiosInstance.put(`/admin/attributes/${editForm.id}`, {
            name: editForm.name.trim(),
        });
        notify.success("Attribute updated");
        closeEditModal();
        await refresh();
    } catch (error) {
        notify.error("Failed to update attribute");
    } finally {
        updating.value = false;
    }
};

const openValuesModal = (attribute) => {
    currentAttribute.value = attribute;
    valueForm.id = null;
    valueForm.attribute_id = attribute.id;
    valueForm.value = "";
    valueForm.color_code = "";
    showValuesModal.value = true;
};

const closeValuesModal = () => {
    showValuesModal.value = false;
    currentAttribute.value = null;
};

const editValue = (value) => {
    valueForm.id = value.id;
    valueForm.attribute_id = value.attribute_id;
    valueForm.value = value.value;
    valueForm.color_code = value.color_code || "";
};

const resetValueForm = () => {
    valueForm.id = null;
    valueForm.attribute_id = currentAttribute.value?.id || null;
    valueForm.value = "";
    valueForm.color_code = "";
};

const saveValue = async () => {
    if (!valueForm.attribute_id) {
        notify.error("Select an attribute first");
        return;
    }
    if (!valueForm.value.trim()) {
        notify.error("Value is required");
        return;
    }

    savingValue.value = true;
    try {
        if (valueForm.id) {
            await axiosInstance.put(`/admin/attribute-values/${valueForm.id}`, {
                attribute_id: valueForm.attribute_id,
                value: valueForm.value.trim(),
                color_code: valueForm.color_code || null,
            });
            notify.success("Value updated");
        } else {
            await axiosInstance.post("/admin/attribute-values", {
                attribute_id: valueForm.attribute_id,
                value: valueForm.value.trim(),
                color_code: valueForm.color_code || null,
            });
            notify.success("Value added");
        }
        await refresh();
        const updated = attributes.value.find(
            (item) => item.id === valueForm.attribute_id
        );
        if (updated) {
            currentAttribute.value = updated;
        }
        resetValueForm();
    } catch (error) {
        notify.error("Failed to save value");
    } finally {
        savingValue.value = false;
    }
};

const displayedValues = (attribute) => {
    return (attribute.values || []).slice(0, 4);
};
</script>

<template>
    <section class="space-y-6 px-6 py-6">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-semibold text-slate-900">Attributes</h2>
            <p class="text-sm text-slate-500">
                Create attributes and manage their values.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <ActionButton type="button" @click="openCreateModal">
                    <Plus class="h-4 w-4" />
                    Add Attribute
                </ActionButton>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <div class="relative flex-1">
                    <Search
                        class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 mt-2" />
                    <PlainTextInput
                        v-model="attributeFilters.search"
                        placeholder="Search attributes"
                        type="text"
                        class="pl-10"
                    />
                </div>
                <div class="relative">
                    <SelectInput v-model.number="attributeFilters.perPage" :options="perPageOptions" />
                </div>
            </div>

            <AppTable :loading="loading" :columns="4" :rows="6">
                <template #head>
                    <tr>
                        <th class="px-4 py-3">Attribute</th>
                        <th class="px-4 py-3">Values</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3 text-right">Manage</th>
                    </tr>
                </template>
                <AppTableRow v-for="attribute in attributes" :key="attribute.id">
                    <AppTableCell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                                <SlidersHorizontal class="h-5 w-5" />
                            </div>
                            <span class="font-semibold text-slate-900">{{ attribute.name }}</span>
                        </div>
                    </AppTableCell>
                    <AppTableCell>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="value in displayedValues(attribute)"
                                :key="value.id"
                                class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"
                            >
                                <span
                                    v-if="value.color_code"
                                    class="h-2.5 w-2.5 rounded-full border border-black/10"
                                    :style="{ backgroundColor: value.color_code }"
                                ></span>
                                {{ value.value }}
                            </span>
                            <span
                                v-if="(attribute.values || []).length > 4"
                                class="text-xs font-semibold text-slate-400"
                            >
                                +{{ (attribute.values || []).length - 4 }} more
                            </span>
                        </div>
                    </AppTableCell>
                    <AppTableCell>
                        <span class="text-sm font-semibold text-slate-600">{{ attribute.values_count ?? 0 }}</span>
                    </AppTableCell>
                    <AppTableCell class="text-right">
                        <div class="flex justify-end gap-2">
                            <EditButton type="button" @click="openEditModal(attribute)" />
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                                @click="openValuesModal(attribute)">
                                Values
                            </button>
                        </div>
                    </AppTableCell>
                </AppTableRow>
            </AppTable>

            <div class="mt-5 overflow-x-auto">
                <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600 min-w-max">
                    <span v-if="pagination?.meta">
                        {{ pagination.meta.from ?? 0 }}-{{ pagination.meta.to ?? 0 }} of
                        {{ pagination.meta.total ?? 0 }}
                    </span>
                    <TailwindPagination v-if="pagination" :data="pagination" @pagination-change-page="changePage" />
                </div>
            </div>
        </div>

        <AppModal v-model="showCreateModal" title="Add Attribute" @close="closeCreateModal">
            <div class="space-y-4">
                <div>
                    <FormLabel>Attribute Name</FormLabel>
                    <PlainTextInput v-model="createForm.name" placeholder="Enter attribute name" type="text" />
                </div>
            </div>
            <template #footer>
                <PrimaryButton type="button" :disabled="creating" @click="createAttribute">
                    {{ creating ? "Creating..." : "Create Attribute" }}
                </PrimaryButton>
            </template>
        </AppModal>

        <AppModal v-model="showEditModal" title="Edit Attribute" @close="closeEditModal">
            <div class="space-y-4">
                <div>
                    <FormLabel>Attribute Name</FormLabel>
                    <PlainTextInput v-model="editForm.name" placeholder="Enter attribute name" type="text" />
                </div>
            </div>
            <template #footer>
                <PrimaryButton type="button" :disabled="updating" @click="updateAttribute">
                    {{ updating ? "Updating..." : "Update Attribute" }}
                </PrimaryButton>
            </template>
        </AppModal>

        <AppModal v-model="showValuesModal" title="Manage Values" maxWidthClass="max-w-3xl" @close="closeValuesModal">
            <div v-if="currentAttribute" class="space-y-6">
                <div class="rounded-xl border border-slate-200 px-4 py-3">
                    <div class="text-xs font-semibold text-slate-500">Attribute</div>
                    <div class="mt-1 text-sm font-semibold text-slate-700">{{ currentAttribute.name }}</div>
                </div>

                <div class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <FormLabel>Attribute</FormLabel>
                            <SelectInput
                                v-model.number="valueForm.attribute_id"
                                :options="attributeOptions"
                            />
                        </div>
                        <div>
                            <FormLabel>Value</FormLabel>
                            <PlainTextInput v-model="valueForm.value" placeholder="Enter value" type="text" />
                        </div>
                        <div>
                            <FormLabel>Color Code</FormLabel>
                            <PlainTextInput v-model="valueForm.color_code" placeholder="#000000" type="text" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <PrimaryButton type="button" :disabled="savingValue" @click="saveValue">
                            {{ savingValue ? "Saving..." : (valueForm.id ? "Update Value" : "Add Value") }}
                        </PrimaryButton>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 max-h-[50vh] overflow-y-auto">
                    <div class="border-b border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                        Values
                    </div>
                    <div class="divide-y divide-slate-200">
                        <div
                            v-for="value in currentAttribute.values || []"
                            :key="value.id"
                            class="flex flex-wrap items-center justify-between gap-3 px-4 py-3"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    v-if="value.color_code"
                                    class="h-4 w-4 rounded-full border border-black/10"
                                    :style="{ backgroundColor: value.color_code }"
                                ></span>
                                <div class="text-sm font-semibold text-slate-700">{{ value.value }}</div>
                            </div>
                            <button
                                type="button"
                                class="text-xs font-semibold text-primary-600 hover:text-primary-700"
                                @click="editValue(value)"
                            >
                                Edit
                            </button>
                        </div>
                        <div v-if="!(currentAttribute.values || []).length" class="px-4 py-6 text-sm text-slate-500">
                            No values yet. Add the first value above.
                        </div>
                    </div>
                </div>
            </div>
        </AppModal>
    </section>
</template>
