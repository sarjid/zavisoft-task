<template>

    <div class="min-h-screen bg-body text-slate-900">
        <div class="flex">
            <div v-if="isSidebarOpen" class="fixed inset-0 z-30 bg-black/30 md:hidden" @click="isSidebarOpen = false">
            </div>

            <AdminSidebar
                :is-open="isSidebarOpen"
                :is-expanded="isSidebarExpanded"
                :show-user-menu="showUserMenu"
                @toggle-sidebar="toggleSidebar"
                @toggle-user-menu="showUserMenu = !showUserMenu"
            />

            <main class="flex-1" @click="showUserMenu = false; showHeaderUserMenu = false">
                <header
                    class="flex flex-col gap-6 border-b border-slate-200 bg-white px-6 py-5 md:flex-row md:items-center md:justify-between">
                    <div class="flex w-full items-center gap-3">
                        <button
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-600 md:hidden"
                            type="button" aria-label="Open menu" @click="isSidebarOpen = !isSidebarOpen">
                            <component :is="isSidebarOpen ? X : Menu" class="h-5 w-5" />
                        </button>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-50">
                            <span class="text-xl font-bold text-primary-600">Z</span>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Zavishop</p>
                            <h1 class="text-lg font-semibold text-slate-900">Bangladesh</h1>
                        </div>
                        <div class="ml-auto md:hidden">
                            <AdminUserMenu
                                variant="header"
                                :is-open="showHeaderUserMenu"
                                :show-profile-actions="false"
                                @toggle="showHeaderUserMenu = !showHeaderUserMenu"
                            />
                        </div>
                    </div>
                </header>

                <RouterView />
            </main>
        </div>
    </div>

</template>

<script setup>
import { RouterView } from 'vue-router';
import AdminSidebar from '@/components/admin/AdminSidebar.vue';
import AdminUserMenu from '@/components/admin/AdminUserMenu.vue';
import { useAdminLayout } from '@/composables/useAdminLayout';
import { Menu, X } from 'lucide-vue-next';

const {
    isSidebarOpen,
    isSidebarExpanded,
    showUserMenu,
    showHeaderUserMenu,
    toggleSidebar,
} = useAdminLayout();
</script>
