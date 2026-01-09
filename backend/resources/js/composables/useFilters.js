import { ref, watch } from "vue";
import { debounce, pickBy } from "lodash";
import axiosInstance from "@/services/AxiosService";

export default function useFilters(filters, endpoint, debounceTime = 400) {
    const data = ref([]);
    const pagination = ref(null);
    const meta = ref(null);
    const loading = ref(false);
    const error = ref(null);

    const fetchData = async () => {
        loading.value = true;
        error.value = null;
        try {
            const query = pickBy(
                filters,
                (value) => value !== null && value !== undefined && value !== ""
            );
            const res = await axiosInstance.get(endpoint, { params: query });
            pagination.value = res.data ?? null;
            data.value = pagination.value?.data ?? [];
            meta.value = pagination.value?.meta ?? null;
        } catch (err) {
            error.value = err;
        } finally {
            loading.value = false;
        }
    };

    watch(filters, debounce(fetchData, debounceTime), { deep: true, immediate: true });

    return {
        data,
        pagination,
        meta,
        loading,
        error,
        refresh: fetchData,
    };
}
