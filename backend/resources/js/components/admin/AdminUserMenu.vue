<script setup>
import { computed } from 'vue';
import { KeyRound, LogOut, User, UserCircle } from 'lucide-vue-next';
import { useAuth } from '@/stores';
import { useRouter } from "vue-router";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    variant: {
        type: String,
        default: 'sidebar',
    },
    showLabel: {
        type: Boolean,
        default: true,
    },

    userName: {
        type: String,
        default: 'Admin',
    },
    userEmail: {
        type: String,
        default: 'admin@gmail.com',
    },
});

defineEmits(['toggle']);

const triggerClass = computed(() => {
    if (props.variant === 'header') {
        return 'inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-600';
    }

    return 'flex h-11 w-full items-center justify-center gap-3 rounded-xl bg-slate-100 px-3 text-sm font-medium text-slate-600 transition cursor-pointer md:justify-start';
});

const iconClass = computed(() => (props.variant === 'header' ? 'h-5 w-5' : 'h-5 w-5'));

const menuClass = computed(() => {
    if (props.variant === 'header') {
        return 'absolute right-0 top-12 w-56 rounded-xl border border-slate-200 bg-white p-3 shadow-lg';
    }

    return 'absolute bottom-14 left-0 w-64 rounded-xl border border-slate-200 bg-white p-3 shadow-lg';
});



const auth = useAuth();
const router = useRouter();
const handleLogout = async () => {
    try {
        const res = await auth.logout();
        if (res.data.status) {
            router.push({ name: 'admin-login' });
        }
    } catch (error) {

    }
}
</script>


<template>
    <div class="relative" @click.stop>
        <button :class="triggerClass" type="button" aria-label="User menu" @click="$emit('toggle')">
            <UserCircle :class="iconClass" />
            <span v-if="variant === 'sidebar' && showLabel" class="md:inline">Admin</span>
        </button>

        <div v-if="isOpen" :class="menuClass">
            <div class="flex items-start gap-3 border-b border-slate-100 pb-3">
                <div class="relative">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-50 text-primary-600">
                        <UserCircle class="h-5 w-5" />
                    </div>
                    <span
                        class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white bg-green-500"></span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ userName }}</p>
                    <p class="text-xs text-slate-500">{{ userEmail }}</p>
                </div>
            </div>
            <div class="flex flex-col gap-1 pt-2 text-sm font-medium text-slate-600">

                <button @click.prevent="handleLogout"
                    class="flex items-center gap-2 rounded-lg px-2 py-2 text-left text-primary-600 hover:bg-primary-50 cursor-pointer"
                    type="button">
                    <LogOut class="h-4 w-4" />
                    Logout
                </button>
            </div>
        </div>
    </div>
</template>
