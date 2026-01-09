export default function useToggleStatus(updateStatus, options = {}) {
    const statusKey = options.statusKey ?? "status";
    const mapStatus = options.mapStatus ?? ((isActive) => (isActive ? 1 : 0));

    const toggleStatus = async (item, isActive) => {
        const nextStatus = mapStatus(isActive);
        const previousStatus = item[statusKey];
        item[statusKey] = nextStatus;
        try {
            await updateStatus(item, nextStatus);
        } catch (error) {
            item[statusKey] = previousStatus;
        }
    };

    return {
        toggleStatus,
    };
}
