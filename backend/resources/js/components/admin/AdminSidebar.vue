<template>
    <aside
        class="fixed left-0 top-0 z-40 flex h-screen w-1/2 flex-col border-r border-slate-200 bg-white py-6 transition-[transform,width] duration-200 sm:w-64 md:sticky md:top-0 md:w-20 md:flex-shrink-0 md:translate-x-0"
        :class="[
            isOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
            isExpanded ? 'md:w-72' : 'md:w-20',
        ]">
        <div class="flex w-full items-center justify-center gap-3 px-4 md:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-50">
                    <span class="text-xl font-bold text-primary-600">Z</span>
                </div>
                <div v-show="showLabels" class="md:block">
                    <p class="text-lg font-semibold text-slate-900">Zavishop</p>
                    <p class="text-xs font-medium uppercase tracking-[0.3em] text-slate-400">Bangladesh</p>
                </div>
            </div>
            <button
                class="hidden h-10 w-10 items-center justify-center rounded-full bg-primary-500 text-white shadow-sm md:flex"
                type="button" aria-label="Toggle menu" @click="handleToggleSidebar">
                <component :is="isOpen ? X : (isExpanded ? PanelLeft : Menu)" class="h-5 w-5" />
            </button>
        </div>

        <nav class="mt-6 flex flex-1 flex-col gap-1 px-3 text-slate-600">
            <RouterLink
                v-for="item in primaryMenuItems"
                :key="item.label"
                :to="{ name: item.link }"
                class="flex h-11 w-full items-center justify-center gap-3 rounded-xl px-3 text-sm font-medium transition hover:bg-slate-100"
                :class="[
                    isExpanded ? 'md:justify-start' : 'md:justify-center md:px-0',
                    isActiveRoute(item.link) ? 'bg-primary-50 font-semibold text-primary-600' : '',
                ]"
                :aria-label="item.label">
                <component :is="item.icon" class="h-5 w-5" />
                <span v-show="showLabels" class="md:inline">{{ item.label }}</span>
            </RouterLink>

        </nav>

        <div class="mx-3 hidden w-full md:block">
            <AdminUserMenu :is-open="showUserMenu" :show-label="isExpanded" @toggle="handleToggleUserMenu" />
        </div>
    </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import AdminUserMenu from '@/components/admin/AdminUserMenu.vue';
import {
    Folder,
    PanelLeft,
    Layers,
    Menu,
    Package,
    ShieldCheck,
    ShoppingBag,
    SlidersHorizontal,
    Store,
    Users,
    X,
} from 'lucide-vue-next';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    isExpanded: {
        type: Boolean,
        default: true,
    },
    showUserMenu: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['toggle-sidebar', 'toggle-user-menu']);

const showLabels = computed(() => props.isExpanded || props.isOpen);

const route = useRoute();

const isActiveRoute = (routeName) => {
    return route.name === routeName;
};

const primaryMenuItems = [
    { label: 'Dashboard', icon: PanelLeft, link: 'admin-dashboard' },
    { label: 'Categories', icon: Layers, link: 'admin-categories' },
    { label: 'Products', icon: Layers, link: 'admin-products' },
    // { label: 'Products', icon: Package, link: 'admin-products' },
    // { label: 'Orders', icon: ShoppingBag, link: 'admin-orders' },
];


const handleToggleSidebar = () => {
    emit('toggle-sidebar');
};

const handleToggleUserMenu = () => {
    emit('toggle-user-menu');
};
</script>
