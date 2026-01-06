<script setup>
import { ShoppingCart } from 'lucide-vue-next';
import { RouterLink } from 'vue-router';
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
  <div>
    <header class="sticky top-0 left-0 w-full z-50 transition-shadow duration-150 bg-white  shadow-none mx-auto">

      <div class="w-full bg-white  py-2 md:py-3 px-2 md:px-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 md:gap-6 px-4 md:px-0">
          <div class="cursor-pointer flex items-center gap-2 shrink-0">

            <RouterLink to="/" class="w-[100px] md:w-[125px] relative">
              <AppLogo
                class="max-h-[40px] md:max-h-[50px] object-left object-contain transition-all duration-300 hover:opacity-80 hover:scale-105 cursor-pointer" />
            </RouterLink>
          </div>
          <div class="hidden md:flex flex-1 justify-center w-full max-w-2xl px-4">
            <div class="w-full">
              <div class="px-2 md:px-6 lg:px-8 w-full hidden md:block">
                <div class="max-w-4xl mx-auto relative">
                  <div
                    class="relative group bg-white/80 backdrop-blur-lg rounded-xl transition-all duration-300 ring-1 ring-gray-200 hover:ring-primary-200 ">
                    <input type="text" placeholder="search products"
                      class="w-full h-14 pl-6 pr-14 bg-transparent outline-none text-gray-900 placeholder-gray-400 transition-all duration-300"
                      value="">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
                      <div
                        class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 transition-transform duration-200 hover:scale-105 cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-3 text-sm font-semibold text-slate-700">
            <ul class="md:hidden flex items-center gap-2 md:gap-3"></ul>

            <RouterLink to="/cart" class="rounded-full px-3 py-2 hover:bg-slate-100">
              Admin
            </RouterLink>


            <RouterLink to="/cart"
              class="relative w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center hover:text-primary-600">

              <ShoppingCart />
              <span
                class="absolute -top-0 -right-0 bg-red-500 text-white text-[9px] md:text-[10px] font-bold w-3 h-3 md:w-4 md:h-4 rounded-full flex items-center justify-center">2</span>
            </RouterLink>
          </div>
        </div>
      </div>
      <div class="md:hidden w-full bg-white px-4"></div>
    </header>



    <div class="block md:hidden px-2 md:px-6 lg:px-8 mt-5">

      <div class="max-w-4xl mx-auto relative">
        <div
          class="relative group bg-white/80 backdrop-blur-lg rounded-xl transition-all duration-300 ring-1 ring-gray-200 hover:ring-primary-600 ">
          <input type="text" placeholder="Search your desired product"
            class="w-full h-14 pl-6 pr-14 bg-transparent outline-hidden text-gray-900 placeholder-gray-400 transition-all duration-300"
            value="">
          <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
            <div
              class="w-10 h-10 flex items-center justify-center rounded-lg bg-linear-to-r from-primary-500 to-primary-600 transition-transform duration-200 hover:scale-105 cursor-pointer">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <main class=" max-w-7xl w-full mx-auto px-2 sm:px-4 md:px-6 lg:px-1">
      <RouterView />
    </main>


    <footer class="border-t border-gray-200  mt-16 pt-12 pb-6 bg-white">
      <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
          <div class="space-y-4"><a class="mb-4" href="/">

              <RouterLink to="/" class="w-[100px] md:w-[125px] relative">
                <AppLogo
                  class="h-[40px] w-auto max-w-[180px] object-contain transition-all duration-300 hover:opacity-80 hover:scale-110 hover:rotate-1 cursor-pointer" />
              </RouterLink>

            </a>
            <div class="flex space-x-4"></div>
          </div>
          <div>
            <h4 class="font-medium text-lg mb-5">Policies</h4>
            <div class="flex flex-col gap-3 text-sm"><a
                class="flex items-center text-gray-600  hover:text-primary-600  transition-colors duration-200 group"
                href="/about-us"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-chevron-right w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300">
                  <path d="m9 18 6-6-6-6"></path>
                </svg><span class="capitalize">about us</span></a><a
                class="flex items-center text-gray-600  hover:text-primary-600 transition-colors duration-200 group"
                href="/privacy-policy"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-chevron-right w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300">
                  <path d="m9 18 6-6-6-6"></path>
                </svg><span class="capitalize">privacy policy</span></a><a
                class="flex items-center text-gray-600  hover:text-primary-600  transition-colors duration-200 group"
                href="/terms-and-conditions"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-chevron-right w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300">
                  <path d="m9 18 6-6-6-6"></path>
                </svg><span class="capitalize">terms and conditions</span></a><a
                class="flex items-center text-gray-600 hover:text-primary-600  transition-colors duration-200 group"
                href="/return-and-cancellation-policy"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-chevron-right w-4 h-4 mr-2 opacity-0 -ml-6 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300">
                  <path d="m9 18 6-6-6-6"></path>
                </svg><span class="capitalize">return and cancellation policy</span></a></div>
          </div>
          <div>
            <h4 class="font-medium text-lg mb-5">Contact Us</h4>
            <div class="flex flex-col space-y-3 text-sm text-gray-600">
              <div class="flex items-start space-x-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4 mt-0.5">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg><span class="whitespace-pre-line">House #44, Road 02, ASA Center, Zigatola, Dhanmondi-1209</span>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-12 pt-6 border-t border-gray-200  text-center text-sm text-gray-500 
          ">
          <p>© 2025 - Zavishop Bangladesh</p>
        </div>
      </div>
    </footer>
  </div>

</template>
