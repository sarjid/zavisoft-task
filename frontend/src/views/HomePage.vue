<script setup>
import CategoryCarousel from '@/components/CategoryCarousel.vue'
import CategoryList from '@/components/CategoryList.vue'
import ProductCard from '@/components/ProductCard.vue'

const categories = [
    { name: 'Health & Beauty', image: '/categories/1.jpg' },
    { name: 'Home Decor', image: '/products/2.jpg' },
    { name: 'Home & Kitchen', image: '/products/3.jpg' },
]

const products = [
    {
        id: 1,
        title: 'Multi Layer Shoe Rack',
        image: '/products/1.jpg',
    },
    {
        id: 2,
        title: 'Hair Lock Premium Organic Hair Growth Oil- 250 ML',
        image: '/products/2.jpg',
    },
    {
        id: 3,
        title: 'Tudo Dish Rack',
        image: '/products/3.jpg',
    },

    {
        id: 4,
        title: 'Tudo Dish Rack 2',
        image: '/products/4.jpg',
    },

]
</script>

<template>
    <div class="grid xl:grid-cols-5 gap-5 py-2 text-primary-900">
        <div
            class="overflow-hidden -mr-5 xl:overflow-auto xl:-mr-0 xl:bg-white xl:rounded-xl xl:border xl:border-primary-50 xl:h-[calc(100vh-120px)] xl:sticky xl:top-[100px] xl:left-0 xl:self-start col-span-full xl:col-span-1 shadow-sm">
            <li class="mx-4 mt-4 mb-4 hidden font-semibold text-secondary-500 xl:block">
                Categories
            </li>

            <div class="hidden xl:block">
                <div class="hover:bg-primary-50/60">
                    <div class="px-5 py-4 bg-primary-50 flex items-center rounded-t-xl">
                        <span class="w-full font-bold text-lg text-secondary-500">Category List</span>
                    </div>
                    <CategoryList v-for="category in categories" :key="category.id" :category="category" />
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
                            class="relative w-[120px] min-w-[120px] aspect-[4/5] rounded-2xl overflow-hidden bg-primary-100 shadow-sm border border-primary-50">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-primary-500/90 via-primary-600/70 to-primary-900/70">
                            </div>
                            <div class="absolute inset-0 flex flex-col justify-between p-3">
                                <div class="text-xs font-semibold text-white/80">All</div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white leading-tight">All products</h3>
                                    <span class="mt-1 inline-flex items-center text-xs text-white/80">120+ items</span>
                                </div>
                            </div>
                        </div>

                        <CategoryCarousel v-for="category in categories" :key="category.id" :category="category" />
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
                                class="text-input py-2 w-[122px] text-sm px-2 mt-0 rounded-lg bg-white border border-primary-50 text-primary-900">
                                <option value="">Default</option>
                                <option value="ASC">Price (Low &gt; High)</option>
                                <option value="DESC">Price (High &gt; Low)</option>
                            </select></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-1 md:gap-4">
                        <ProductCard v-for="product in products" :key="product.id" :product="product" />
                    </div>
                    <div class="w-full mx-auto mt-12 px-4 text-gray-600 md:px-8">
                        <div class="justify-center flex" aria-label="Pagination">
                            <ul class="flex items-center gap-4">
                                <li
                                    class="bg-primary-200 cursor-not-allowed text-primary-800 px-4 py-2.5 border border-primary-100 rounded-lg">
                                    Previous Page
                                </li>
                                <li class="text-secondary-500 font-semibold"><span>1 / 1</span></li>
                                <li
                                    class="bg-primary-200 cursor-not-allowed text-primary-800 px-4 py-2.5 border border-primary-100 rounded-lg">
                                    Next Page
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </ul>
        </div>
    </div>
</template>
