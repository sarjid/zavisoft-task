<script setup>
import CategoryCarousel from '@/components/CategoryCarousel.vue'
import CategoryList from '@/components/CategoryList.vue'
import ProductCard from '@/components/ProductCard.vue'
import { SearchX } from 'lucide-vue-next'
import { useCategoryProducts } from '@/composables/useCategoryProducts'

const {
    categories,
    products,
    productsLoading,
    productsHasMore,
    selectedCategorySlug,
    sortOrder,
    infiniteScrollSentinel,
    selectCategory,
    clearCategory,
    setSortOrder,
} = useCategoryProducts({ perPage: 8, routeName: 'products', queryKey: 'category' })
const productSkeletons = Array.from({ length: 8 }, (_, index) => index)
</script>

<template>
    <div class="grid xl:grid-cols-5 gap-5 py-2 text-primary-900">
        <div class="overflow-hidden -mr-5 xl:overflow-auto xl:-mr-0 xl:bg-white xl:rounded-xl xl:border xl:border-primary-50 xl:h-[calc(100vh-120px)] xl:sticky xl:top-[100px] xl:left-0 xl:self-start col-span-full xl:col-span-1 shadow-sm">
            <li class="mx-4 mt-4 mb-4 hidden font-semibold text-secondary-500 xl:block">
                Categories
            </li>

            <div class="hidden xl:block">
                <div>
                    <div class="px-5 py-4 bg-primary-50 flex items-center rounded-t-xl">
                        <span class="w-full font-bold text-lg text-secondary-500">Category List</span>
                    </div>
                    <CategoryList :category="{ id: null, name: 'All Products', slug: null }"
                        :isActive="!selectedCategorySlug"
                        @select="clearCategory" />
                    <CategoryList v-for="category in categories" :key="category.id" :category="category"
                        :isActive="selectedCategorySlug === category.slug" @select="selectCategory" />
                </div>
            </div>



            <!-- mobile category  -->
            <div class="block xl:hidden">
                <div class="px-2 mb-2">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-secondary-500">Browse categories</h3>
                    </div>
                    <div class="flex overflow-x-auto gap-3 pb-2 scroll-mb-1 category-x-scrollbar">
                        <div
                            class="relative w-[120px] min-w-[120px] aspect-[4/5] rounded-2xl overflow-hidden bg-primary-100 shadow-sm border border-primary-50 cursor-pointer"
                            :class="!selectedCategorySlug ? 'ring-2 ring-primary-200' : ''"
                            @click="clearCategory">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-primary-500/90 via-primary-600/70 to-primary-900/70">
                            </div>
                            <div class="absolute inset-0 flex flex-col justify-between p-3">
                                <div class="text-xs font-semibold text-white/80">All</div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white leading-tight">All products</h3>
                                    <!-- <span class="mt-1 inline-flex items-center text-xs text-white/80">120+ items</span> -->
                                </div>
                            </div>
                        </div>
                        <CategoryCarousel v-for="category in categories" :key="category.id" :category="category"
                            @select="selectCategory" />
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4">
            <ul class="space-y-6">
                <div>
                  
                    <div
                        class="px-4 h-[55px] bg-white rounded-xl mb-3 border border-primary-50 flex items-center justify-between shadow-sm">
                        <h2 class="font-semibold text-secondary-500 truncate w-[45%]">
                            Products
                        </h2>
                        <div class="text-primary-900 text-sm flex items-center gap-2"><span class="min-w-fit">Sort
                                by:</span><select
                                v-model="sortOrder"
                                @change="setSortOrder(sortOrder)"
                                class="text-input py-2 w-[122px] text-sm px-2 mt-0 rounded-lg bg-white border border-primary-50 text-primary-900">
                                <option value="">Default</option>
                                <option value="ASC">Price (Low &gt; High)</option>
                                <option value="DESC">Price (High &gt; Low)</option>
                            </select></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-1 md:gap-4">
                        <template v-if="productsLoading">
                            <div v-for="item in productSkeletons" :key="item"
                                class="bg-white rounded-md shadow-sm border border-primary-50 overflow-hidden animate-pulse">
                                <div class="p-3">
                                    <div class="h-40 sm:h-44 bg-primary-100 rounded-lg"></div>
                                    <div class="mt-4 h-4 bg-primary-100 rounded"></div>
                                    <div class="mt-2 h-4 bg-primary-100 rounded w-3/4"></div>
                                </div>
                                <div class="px-4 pb-4">
                                    <div class="h-10 bg-primary-100 rounded-lg"></div>
                                </div>
                            </div>
                        </template>
                        <ProductCard v-else v-for="product in products" :key="product.id" :product="product" />
                    </div>
                    <div v-if="!productsLoading && !products.length"
                        class="flex flex-col items-center justify-center text-center py-16 px-6 bg-white rounded-xl border border-primary-50 mt-6">
                        <div
                            class="h-16 w-16 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center">
                            <SearchX class="h-8 w-8" />
                        </div>
                        <h2 class="mt-4 text-xl font-semibold text-secondary-600">No result found</h2>
                        <p class="mt-2 text-sm text-secondary-500 max-w-md">
                            No products are available for this category right now. Try another category.
                        </p>
                    </div>
                    <div class="w-full mx-auto mt-8 px-4 text-gray-600 md:px-8">
                        <div v-if="productsLoading && products.length"
                            class="text-center text-sm text-secondary-500">
                            Loading more products...
                        </div>
                        <div v-else-if="!productsHasMore && products.length"
                            class="text-center text-sm text-secondary-500">
                            No more products
                        </div>
                        <div ref="infiniteScrollSentinel" class="h-6"></div>
                    </div>
                </div>
            </ul>
        </div>
    </div>
</template>
