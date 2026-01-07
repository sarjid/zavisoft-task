<script setup>
import { ChevronRight, MapPin } from 'lucide-vue-next';
import { RouterLink } from 'vue-router';
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
  <footer class="border-t border-gray-200 mt-16 pt-12 pb-6 bg-white">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="space-y-4">
          <a class="mb-4" href="/">
            <RouterLink to="/" class="w-[100px] md:w-[125px] relative">
              <AppLogo
                class="h-[40px] w-auto max-w-[180px] object-contain transition-all duration-300 hover:opacity-80 hover:scale-110 hover:rotate-1 cursor-pointer" />
            </RouterLink>
          </a>
          <div class="flex space-x-4"></div>
        </div>
        <div>
          <h4 class="font-medium text-lg mb-5">Policies</h4>
          <div class="flex flex-col gap-3 text-sm">
            <a class="flex items-center text-gray-600 hover:text-primary-600 transition-colors duration-200 group"
              href="/about-us">
              <ChevronRight
                class="w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" />
              <span class="capitalize">about us</span>
            </a>
            <a class="flex items-center text-gray-600 hover:text-primary-600 transition-colors duration-200 group"
              href="/privacy-policy">
              <ChevronRight
                class="w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" />
              <span class="capitalize">privacy policy</span>
            </a>
            <a class="flex items-center text-gray-600 hover:text-primary-600 transition-colors duration-200 group"
              href="/terms-and-conditions">
              <ChevronRight
                class="w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" />
              <span class="capitalize">terms and conditions</span>
            </a>
            <a class="flex items-center text-gray-600 hover:text-primary-600 transition-colors duration-200 group"
              href="/return-and-cancellation-policy">
              <ChevronRight
                class="w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" />
              <span class="capitalize">return and cancellation policy</span>
            </a>
          </div>
        </div>
        <div>
          <h4 class="font-medium text-lg mb-5">Contact Us</h4>
          <div class="flex flex-col space-y-3 text-sm text-gray-600">
            <div class="flex items-start space-x-2">
              <MapPin class="w-4 h-4 mt-0.5" />
              <span class="whitespace-pre-line">House #44, Road 02, ASA Center, Zigatola, Dhanmondi-1209</span>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-12 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
        <p>© 2025 - Zavishop Bangladesh</p>
      </div>
    </div>
  </footer>
</template>
