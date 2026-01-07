<script setup>
import { ShoppingCart, Zap } from 'lucide-vue-next';

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <RouterLink to="/products/abc"
    class="bg-white rounded-md shadow-sm border border-primary-50 hover:border-primary-600 overflow-hidden flex flex-col transition-all duration-200 hover:shadow-lg">
    <div role="button" class="relative">
      <div class="relative w-full h-48 sm:h-52 overflow-hidden">
        <img :alt="product.title" loading="lazy" decoding="async" data-nimg="fill"
          class="w-full h-full object-cover transition-transform duration-300 hover:scale-105 p-2 rounded-xl hover:shadow-sm"
          :src="product.image"
          style="position: absolute; height: 100%; width: 100%; inset: 0; color: transparent; user-select: none;" />
        <div class="absolute bottom-3 right-2 md:top-3 md:right-3">
          <span class="bg-primary-100 text-primary-800 text-xs font-semibold px-2 py-1 rounded-md">300 BDT</span>
        </div>
      </div>
    </div>
    <div class="px-4 py-3 flex flex-col gap-1 items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="text-danger-500 text-sm line-through">Tk 800</span>
        <span class="text-lg sm:text-xl font-bold text-primary-900">Tk 500</span>
      </div>
      <div class="">
        <h3 class="text-center text-primary-900 font-normal text-lg line-clamp-2">{{ product.title }}</h3>
      </div>
    </div>
    <div class="px-4 pb-4 flex flex-col gap-2 mt-auto">
      <div class="w-full">
        <div class="flex flex-col gap-2">


          <!-- <div
           
            class="gap-4 bg-primary-50 rounded-lg p-2 flex items-center justify-between text-primary-800"
          >
            <button>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus text-primary-700 h-5 w-5 md:h-6 md:w-6">
                <path d="M5 12h14"></path>
              </svg>
            </button>
            <span class="text-primary-800 font-medium text-sm md:text-base">1</span>
            <button>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus text-primary-700 h-5 w-5 md:h-6 md:w-6">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
              </svg>
            </button>
          </div> -->


          <button
            class="w-full bg-white border border-primary-600 hover:bg-primary-600 text-primary-700 hover:text-white font-medium py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors">
            <ShoppingCart class="h-4 w-4" /> Add To Cart
          </button>


          <button
            class="w-full bg-secondary-500 hover:bg-secondary-600 text-white border border-secondary-500 font-medium py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors">
            <Zap class="h-4 w-4" /> Buy Now
          </button>
        </div>
      </div>
    </div>
  </RouterLink>
</template>
