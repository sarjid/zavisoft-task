import { computed, ref, watch } from "vue";

export default function useBulkSelection(itemsRef, getId = (item) => item.id) {
    const selectedIds = ref([]);
    const selectAllRef = ref(null);

    const allSelected = computed(() => {
        return itemsRef.value.length > 0 &&
            itemsRef.value.every((item) => selectedIds.value.includes(getId(item)));
    });

    const isIndeterminate = computed(() => {
        return selectedIds.value.length > 0 && !allSelected.value;
    });

    const toggleAll = (event) => {
        if (event.target.checked) {
            selectedIds.value = itemsRef.value.map((item) => getId(item));
        } else {
            selectedIds.value = [];
        }
    };

    const resetSelection = () => {
        selectedIds.value = [];
    };

    watch(itemsRef, resetSelection);

    watch(isIndeterminate, (value) => {
        if (selectAllRef.value) {
            selectAllRef.value.indeterminate = value;
        }
    });

    return {
        selectedIds,
        selectAllRef,
        allSelected,
        isIndeterminate,
        toggleAll,
        resetSelection,
    };
}
