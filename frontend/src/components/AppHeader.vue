<script setup>
import { ref, watch } from 'vue';
import { ShoppingCart } from 'lucide-vue-next';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';
import { useCartStore } from '@/stores/cart';
import AppLogo from '@/components/AppLogo.vue';
import AppSearchBar from '@/components/AppSearchBar.vue';

const route = useRoute();
const router = useRouter();
const searchQuery = ref('');

const cartStore = useCartStore();
const { totalQuantity } = storeToRefs(cartStore);

const syncSearchFromRoute = () => {
  searchQuery.value = typeof route.query.search === 'string' ? route.query.search : '';
};

const handleSearch = () => {
  const nextQuery = {};
  if (route.query.category) {
    nextQuery.category = route.query.category;
  }
  if (route.query.sort) {
    nextQuery.sort = route.query.sort;
  }
  if (searchQuery.value) {
    nextQuery.search = searchQuery.value;
  }
  router.push({ name: 'products', query: nextQuery });
};

syncSearchFromRoute();

watch(
  () => route.query.search,
  () => {
    syncSearchFromRoute();
  }
);
</script>

<template>
  <header class="sticky top-0 left-0 w-full z-50 transition-shadow duration-150 bg-white shadow-none mx-auto">
    <div class="w-full bg-white py-2 md:py-3 px-2 md:px-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 md:gap-6 px-4 md:px-0">
        <div class="cursor-pointer flex items-center gap-2 shrink-0">
          <RouterLink to="/" class="w-[100px] md:w-[125px] relative">
            <AppLogo
              class="max-h-[40px] md:max-h-[50px] object-left object-contain transition-all duration-300 hover:opacity-80 hover:scale-105 cursor-pointer" />
          </RouterLink>
        </div>
        <div class="hidden md:flex flex-1 justify-center w-full max-w-2xl px-4">
          <AppSearchBar
            v-model="searchQuery"
            rootClass="w-full"
            placeholder="search products"
            @search="handleSearch"
          />
        </div>
        <div class="flex items-center gap-3 text-sm font-semibold text-slate-700">
          <ul class="md:hidden flex items-center gap-2 md:gap-3"></ul>
          <RouterLink to="/cart"
            class="relative w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center hover:text-primary-600">
            <ShoppingCart />
            <span
              v-if="totalQuantity"
              class="absolute -top-0 -right-0 bg-red-500 text-white text-[9px] md:text-[10px] font-bold w-3 h-3 md:w-4 md:h-4 rounded-full flex items-center justify-center">{{ totalQuantity }}</span>
          </RouterLink>
        </div>
      </div>
    </div>
    <div class="md:hidden w-full bg-white px-4"></div>
  </header>
</template>
