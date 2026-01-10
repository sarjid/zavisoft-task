<script setup>
import { useRoute, useRouter } from 'vue-router';
import { CheckCircle2 } from 'lucide-vue-next'
const router = useRouter();


const goHome = () => {
  router.push('/');
};
</script>

<template>
  <div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div
      class="max-w-2xl w-full bg-white border border-primary-100 rounded-3xl shadow-xl shadow-black/5 p-8 text-center">
      <div class="mx-auto h-14 w-14 rounded-full bg-primary-50 flex items-center justify-center text-primary-600">
        <CheckCircle2 class="h-7 w-7" />
      </div>
      <h1 class="mt-5 text-2xl font-semibold text-primary-900">Order placed successfully</h1>
      <p class="mt-2 text-sm text-gray-600">
        Thanks for your order. We are preparing it now.
      </p>

      <button type="button"
        class="mt-6 w-full cursor-pointer bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition-colors"
        @click="goHome">
        Continue shopping
      </button>
    </div>
  </div>
</template>
