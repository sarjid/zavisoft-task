<script setup>
import { FileText, Minus, Plus, ZoomIn } from 'lucide-vue-next'
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
        </div>


        <div class="col-span-1 xl:col-span-4">
            <div class="block sm:p-5 max-w-full sm:bg-white h-full rounded-xl sm:border sm:border-primary-50">
                <div style="user-select: none;"></div>
                <div class="flex flex-col xl:flex-row items-start gap-5">
                    <div class="w-full xl:w-[500px]">
                        <div class="relative">
                            <img alt="মাল্টি সেকশন কিচেন অর্গানাইজার" width="512" height="512"
                                class="rounded-[8px] w-full max-h-[400px] md:max-h-[500px] object-contain"
                                src="/products/1.jpg" style="color: transparent; user-select: none;">
                            <!-- <div class="absolute bg-primary-600/15 p-3 rounded-full top-2 right-2 z-10 cursor-pointer">
                                <ZoomIn class="text-white w-5 h-5 md:w-7 md:h-7" />
                            </div> -->
                        </div>
                        <ul class="flex gap-2 flex-wrap mt-2">
                            <li role="button"
                                class="w-[50px] min-w-[50px] h-[50px] sm:min-w-[75px] sm:h-[75px] sm:w-[75px] relative rounded-lg overflow-hidden ring-3 ring-primary-100">
                                <img alt="মাল্টি সেকশন কিচেন অর্গানাইজার_img_0" width="200" height="200"
                                    class="w-full rounded-lg object-cover" src="/products/1.jpg"
                                    style="color: transparent; user-select: none;">
                            </li>
                            <li role="button"
                                class="w-[50px] min-w-[50px] h-[50px] sm:min-w-[75px] sm:h-[75px] sm:w-[75px] relative rounded-lg overflow-hidden ring-3 ring-transparent">
                                <img alt="মাল্টি সেকশন কিচেন অর্গানাইজার_img_1" width="200" height="200"
                                    class="w-full rounded-lg object-cover" src="/products/2.jpg"
                                    style="color: transparent; user-select: none;">
                            </li>
                        </ul>

                    </div>
                    <div class="flex flex-col pt-6 flex-1 w-full">
                        <div class="grid gap-6">
                            <h1 class="text-[20px] md:text-2xl font-medium text-black-full">Product Title Will Go Here
                            </h1>
                            <div class="flex items-end gap-2">
                                <div class="flex items-center gap-2"><span
                                        class="text-xl font-semibold min-w-fit text-black-full">295
                                        BDT</span><span class="text-sm text-[#8E8E8E] min-w-fit line-through">450
                                        BDT</span><span
                                        class="px-2 py-[2px] bg-primary-600 rounded-full text-white text-xs">Save 155
                                        BDT</span></div>
                            </div>
                            <div>
                                <div class="overflow-hidden" style="height: 0px;">
                                    <div></div>
                                </div>
                            </div>
                            <ul class="flex flex-col gap-[18px]">
                                <li class="flex flex-col gap-[12px]"><label
                                        class="text-sm sm:text-base font-[450] text-[#4B5563] capitalize">Quantity</label>
                                    <ul class="flex gap-3 flex-wrap">
                                        <li role="button"
                                            class="lg:pb-[8px] lg:pt-[9px] lg:px-[24px] px-6 pb-2 pt-[9px] cursor-pointer rounded-full text-black-1.2 transition-colors duration-150 flex items-center justify-center gap-[10px] font-medium border-2 border-primary-200 bg-primary-600/10">
                                            <span class="leading-none text-base text-[#4B5563] font-medium capitalize">1
                                                Piece</span>
                                        </li>
                                        <li role="button"
                                            class="lg:pb-[8px] lg:pt-[9px] lg:px-[24px] px-6 pb-2 pt-[9px] cursor-pointer rounded-full border-[1.2px] border-[#D1D5DB] bg-white text-black-1.2 transition-colors duration-150 flex items-center justify-center gap-[10px] font-medium">
                                            <span class="leading-none text-base text-[#4B5563] font-medium capitalize">2
                                                Piece</span>
                                        </li>
                                        <li role="button"
                                            class="lg:pb-[8px] lg:pt-[9px] lg:px-[24px] px-6 pb-2 pt-[9px] cursor-pointer rounded-full border-[1.2px] border-[#D1D5DB] bg-white text-black-1.2 transition-colors duration-150 flex items-center justify-center gap-[10px] font-medium">
                                            <span class="leading-none text-base text-[#4B5563] font-medium capitalize">3
                                                Piece</span>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="w-full my-6">
                            <div class="flex flex-col items-start gap-4">
                                <div
                                    class="flex items-center justify-between gap-4 text-secondary-600 border border-primary-100 rounded-lg px-[10px] py-1">
                                    <button type="button" class="cursor-pointer">
                                        <Minus class="h-5 w-5 md:h-6 md:w-6" />
                                    </button><input type="number"
                                        class="font-medium text-sm md:text-base text-center w-12 md:w-16 border border-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-primary-100 focus:border-transparent [appearance:textfield] [&amp;::-webkit-outer-spin-button]:appearance-none [&amp;::-webkit-inner-spin-button]:appearance-none"
                                        min="0" value="1"><button type="button" class="cursor-pointer">
                                        <Plus class="h-5 w-5 md:h-6 md:w-6" />
                                    </button>
                                </div><button
                                    class="flex-1 p-4 rounded-lg text-sm font-medium disabled:bg-black-disabled transition-colors duration-150 w-full bg-primary-600 border-2 border-primary-100 text-white disabled:text-white disabled:border-black-disabled cursor-pointer">Add
                                    To Cart</button>

                                <button
                                    class="flex-1 p-4 bg-secondary-600 rounded-lg text-sm text-white font-medium disabled:bg-black-disabled transition-colors duration-150 w-full cursor-pointer">Buy
                                    Now</button>
                            </div>
                        </div>
                        <div class="xl:hidden block">
                            <div
                                class="mt-6 rounded-2xl border border-primary-100 bg-gradient-to-br from-primary-50/80 via-white to-secondary-50/70 p-5 shadow-[0_12px_30px_-22px_rgba(0,0,0,0.45)]">
                                <div class="flex flex-col gap-3 mb-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-primary-600/10 text-primary-600">
                                            <FileText class="h-4 w-4" />
                                        </span>
                                        <div>
                                            <h3 class="text-sm md:text-base font-semibold text-secondary-600">
                                                Description</h3>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="text-sm md:text-base text-primary-900 leading-relaxed whitespace-pre-line rounded-xl bg-white/70 border border-primary-50 p-4">
                                    <div>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat veniam
                                            minima, ratione sequi assumenda, vero, quidem totam velit culpa rem ipsum
                                            laudantium sit maxime aliquam ducimus consectetur repellendus atque ullam
                                            fuga eum consequatur quisquam voluptatibus! Cumque distinctio vitae
                                            repellendus quis quasi? Dolorem saepe excepturi qui totam autem quasi
                                            voluptas fugit similique repellat nisi. Architecto perferendis sapiente
                                            provident ipsum itaque sequi quibusdam, dignissimos id cupiditate nulla quos
                                            sed assumenda odio, et voluptatibus, ad deserunt reprehenderit sint! Id
                                            debitis aliquid harum. Molestias nesciunt eaque beatae accusamus. Cumque
                                            illum fuga quos dolor mollitia ab, aliquam excepturi voluptatum vero
                                            repellendus, repudiandae consequuntur obcaecati nulla.
                                        </p>

                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat veniam
                                            minima, ratione sequi assumenda, vero, quidem totam velit culpa rem ipsum
                                            laudantium sit maxime aliquam ducimus consectetur repellendus atque ullam
                                            fuga eum consequatur quisquam e provident ipsum itaque sequi quibusdam,
                                            dignissimos id cupiditate nulla quos sed assumenda odio, et voluptatibus, ad
                                            deserunt reprehenderit sint! Id debitis aliquid harum. Molestias nesciunt
                                            eaque beatae accusamus. Cumque illum fuga quos dolor mollitia ab, aliquam
                                            excepturi voluptatum vero repellendus, repudiandae consequuntur obcaecati
                                            nulla.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden xl:block">
                    <div
                        class="mt-6 rounded-2xl border border-primary-100 bg-gradient-to-br from-primary-50/80 via-white to-secondary-50/70 p-5 shadow-[0_12px_30px_-22px_rgba(0,0,0,0.45)]">
                        <div class="flex flex-col gap-3 mb-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-primary-600/10 text-primary-600">
                                    <FileText class="h-4 w-4" />
                                </span>
                                <div>
                                    <h3 class="text-sm md:text-base font-semibold text-secondary-600">Description</h3>
                                </div>
                            </div>

                        </div>
                        <div
                            class="text-sm md:text-base text-primary-900 leading-relaxed whitespace-pre-line rounded-xl bg-white/70 border border-primary-50 p-4">
                            <div>
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat veniam minima,
                                    ratione sequi assumenda, vero, quidem totam velit culpa rem ipsum laudantium sit
                                    maxime aliquam ducimus consectetur repellendus atque ullam fuga eum consequatur
                                    quisquam voluptatibus! Cumque distinctio vitae repellendus quis quasi? Dolorem saepe
                                    excepturi qui totam autem quasi voluptas fugit similique repellat nisi. Architecto
                                    perferendis sapiente provident ipsum itaque sequi quibusdam, dignissimos id
                                    cupiditate nulla quos sed assumenda odio, et voluptatibus, ad deserunt reprehenderit
                                    sint! Id debitis aliquid harum. Molestias nesciunt eaque beatae accusamus. Cumque
                                    illum fuga quos dolor mollitia ab, aliquam excepturi voluptatum vero repellendus,
                                    repudiandae consequuntur obcaecati nulla.
                                </p>
                                <br>
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat veniam minima,
                                    ratione sequi assumenda, vero, quidem totam velit culpa rem ipsum laudantium sit
                                    maxime aliquam ducimus consectetur repellendus atque ullam fuga eum consequatur
                                    quisquam e provident ipsum itaque sequi quibusdam, dignissimos id cupiditate nulla
                                    quos sed assumenda odio, et voluptatibus, ad deserunt reprehenderit sint! Id debitis
                                    aliquid harum. Molestias nesciunt eaque beatae accusamus. Cumque illum fuga quos
                                    dolor mollitia ab, aliquam excepturi voluptatum vero repellendus, repudiandae
                                    consequuntur obcaecati nulla.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div
                        class="w-full mt-12 rounded-2xl border border-primary-100 bg-gradient-to-br from-primary-50/70 via-white to-secondary-50/70 p-5 shadow-[0_10px_32px_-20px_rgba(0,0,0,0.45)]">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <h4 class="text-xl md:text-2xl font-semibold text-secondary-600">Related products</h4>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-5">
                            <ProductCard v-for="product in products" :key="product.id" :product="product" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
