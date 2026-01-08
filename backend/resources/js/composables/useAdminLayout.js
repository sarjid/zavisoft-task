import { ref } from 'vue';

export const useAdminLayout = () => {
    const isSidebarOpen = ref(false);
    const isSidebarExpanded = ref(true);
    const showUserMenu = ref(false);
    const showHeaderUserMenu = ref(false);

    const toggleSidebar = () => {
        if (isSidebarOpen.value) {
            isSidebarOpen.value = false;
            return;
        }

        isSidebarExpanded.value = !isSidebarExpanded.value;
    };

    return {
        isSidebarOpen,
        isSidebarExpanded,
        showUserMenu,
        showHeaderUserMenu,
        toggleSidebar,
    };
};
