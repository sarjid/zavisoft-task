<script setup>
import AppFooter from '@/components/AppFooter.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppSearchBar from '@/components/AppSearchBar.vue';
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <AppHeader />

    <AppSearchBar
      rootClass="block md:hidden px-2 md:px-6 lg:px-8 mt-5"
      placeholder="Search your desired product"
      ringClass="ring-gray-200 hover:ring-primary-600"
      inputClass="outline-hidden"
      buttonClass="bg-linear-to-r from-primary-500 to-primary-600" />

    <main class="flex-1 max-w-7xl w-full mx-auto px-2 sm:px-4 md:px-6 lg:px-1">
      <RouterView />
    </main>

    <AppFooter />
  </div>
</template>
