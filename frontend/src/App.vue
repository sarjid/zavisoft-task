<script setup>
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AppFooter from '@/components/AppFooter.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppSearchBar from '@/components/AppSearchBar.vue';

const route = useRoute();
const router = useRouter();
const searchQuery = ref('');

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
  <div class="min-h-screen flex flex-col">
    <AppHeader />

    <AppSearchBar
      v-model="searchQuery"
      rootClass="block md:hidden px-2 md:px-6 lg:px-8 mt-5"
      placeholder="Search your desired product"
      ringClass="ring-gray-200 hover:ring-primary-600"
      inputClass="outline-hidden"
      buttonClass="bg-linear-to-r from-primary-500 to-primary-600"
      @search="handleSearch" />

    <main class="flex-1 max-w-7xl w-full mx-auto px-2 sm:px-4 md:px-6 lg:px-1">
      <RouterView />
    </main>

    <AppFooter />
  </div>
</template>
