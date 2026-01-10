<script setup>
import { computed, ref } from 'vue'
import { FileText, Minus, Plus } from 'lucide-vue-next'
import CategoryList from '@/components/CategoryList.vue'
import ProductCard from '@/components/ProductCard.vue'
import { useRouter } from 'vue-router'
import { useProductDetail } from '@/composables/useProductDetail'
import { useCartStore } from '@/stores/cart'
import useNotify from '@/composables/useNotify'

const router = useRouter()
const cartStore = useCartStore()
const notify = useNotify()
const quantity = ref(1)

const {
    categories,
    productDetail,
    productDetailLoading,
    selectedImage,
    selectedOptions,
    galleryImages,
    selectedVariant,
    currentPrice,
    unitPrice,
    saveAmount,
    isOutOfStock,
    stockText,
    activeImage,
    relatedProducts,
    selectOption,
} = useProductDetail({ relatedPerPage: 12 })

const requiresVariant = computed(() => {
    return Boolean(productDetail.value?.has_variant && productDetail.value?.options?.length)
})

const canAddToCart = computed(() => {
    if (isOutOfStock.value) {
        return false
    }
    if (requiresVariant.value && !selectedVariant.value) {
        return false
    }
    return true
})

const handleCategorySelect = (category) => {
    const categorySlug = category?.slug
    router.push({ name: 'products', query: categorySlug ? { category: categorySlug } : {} })
}

const incrementQty = () => {
    quantity.value += 1
}

const decrementQty = () => {
    quantity.value = Math.max(1, quantity.value - 1)
}

const handleAddToCart = () => {
    if (!canAddToCart.value || !productDetail.value) {
        notify.error(isOutOfStock.value ? 'Product is out of stock' : 'Please select variant options')
        return
    }
    cartStore.addItem({
        product: productDetail.value,
        variant: selectedVariant.value,
        selectedOptions: selectedOptions.value,
        quantity: quantity.value,
    })
    notify.success('Added to cart')
}

const handleBuyNow = () => {
    if (!canAddToCart.value || !productDetail.value) {
        notify.error(isOutOfStock.value ? 'Product is out of stock' : 'Please select variant options')
        return
    }
    cartStore.addItem({
        product: productDetail.value,
        variant: selectedVariant.value,
        selectedOptions: selectedOptions.value,
        quantity: quantity.value,
    })
    notify.success('Added to cart')
    router.push('/cart')
}
</script>

<template>
    <div class="grid xl:grid-cols-5 gap-5 py-2 text-primary-900">
        <div
            class="overflow-hidden -mr-5 xl:overflow-auto xl:-mr-0 xl:bg-white xl:rounded-xl xl:border xl:border-primary-50 xl:h-[calc(100vh-120px)] xl:sticky xl:top-[100px] xl:left-0 xl:self-start col-span-full xl:col-span-1 shadow-sm">
            <li class="mx-4 mt-4 mb-4 hidden font-semibold text-secondary-500 xl:block">
                Categories
            </li>

            <div class="hidden xl:block">
                <div>
                    <div class="px-5 py-4 bg-primary-50 flex items-center rounded-t-xl">
                        <span class="w-full font-bold text-lg text-secondary-500">Category List</span>
                    </div>
                    <CategoryList v-for="category in categories" :key="category.id" :category="category"
                        @select="handleCategorySelect" />
                </div>
            </div>
        </div>


        <div class="col-span-1 xl:col-span-4">
            <div class="block sm:p-5 max-w-full sm:bg-white h-full rounded-xl sm:border sm:border-primary-50">
                <div style="user-select: none;"></div>
                <div v-if="productDetailLoading" class="flex flex-col xl:flex-row items-start gap-5 animate-pulse">
                    <div class="w-full xl:w-[500px]">
                        <div class="h-[360px] md:h-[420px] bg-primary-100 rounded-[8px]"></div>
                        <div class="flex gap-2 flex-wrap mt-2">
                            <div v-for="item in 4" :key="item"
                                class="w-[50px] h-[50px] sm:w-[75px] sm:h-[75px] bg-primary-100 rounded-lg"></div>
                        </div>
                    </div>
                    <div class="flex flex-col pt-6 flex-1 w-full">
                        <div class="grid gap-4">
                            <div class="h-6 bg-primary-100 rounded w-3/4"></div>
                            <div class="h-4 bg-primary-100 rounded w-1/3"></div>
                            <div class="h-8 bg-primary-100 rounded w-1/2"></div>
                            <div class="h-4 bg-primary-100 rounded w-1/4"></div>
                            <div class="h-10 bg-primary-100 rounded w-full"></div>
                            <div class="h-10 bg-primary-100 rounded w-full"></div>
                        </div>
                        <div class="w-full my-6">
                            <div class="h-12 bg-primary-100 rounded w-40"></div>
                            <div class="mt-4 h-12 bg-primary-100 rounded w-full"></div>
                            <div class="mt-3 h-12 bg-primary-100 rounded w-full"></div>
                        </div>
                    </div>
                </div>
                <div v-else class="flex flex-col xl:flex-row items-start gap-5">
                    <div class="w-full xl:w-[500px]">
                        <div class="relative">
                            <img :alt="productDetail?.name || 'Product image'" width="512" height="512"
                                class="rounded-[8px] w-full max-h-[400px] md:max-h-[500px] object-contain"
                                :src="activeImage" style="color: transparent; user-select: none;">
                            <!-- <div class="absolute bg-primary-600/15 p-3 rounded-full top-2 right-2 z-10 cursor-pointer">
                                <ZoomIn class="text-white w-5 h-5 md:w-7 md:h-7" />
                            </div> -->
                        </div>
                        <ul class="flex gap-2 flex-wrap mt-2">
                            <li v-for="image in galleryImages" :key="image" role="button"
                                class="w-[50px] min-w-[50px] h-[50px] sm:min-w-[75px] sm:h-[75px] sm:w-[75px] relative rounded-lg overflow-hidden ring-3"
                                :class="selectedImage === image ? 'ring-primary-100' : 'ring-transparent'"
                                @click="selectedImage = image">
                                <img :alt="productDetail?.name || 'Product image'" width="200" height="200"
                                    class="w-full rounded-lg object-cover" :src="image"
                                    style="color: transparent; user-select: none;">
                            </li>
                        </ul>

                    </div>
                    <div class="flex flex-col pt-6 flex-1 w-full">
                        <div class="max-w-xl flex flex-col gap-y-6">
                            <header class="space-y-1">
                                <p v-if="productDetail?.category?.name"
                                    class="text-xs font-bold uppercase tracking-widest text-primary-600">
                                    {{ productDetail.category.name }}
                                </p>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                                    {{ productDetail?.name || 'Product' }}
                                </h1>

                                <div class="flex items-center gap-4 pt-1">
                                    <p v-if="productDetail?.has_variant ? selectedVariant?.sku : productDetail?.sku"
                                        class="text-sm text-gray-500">
                                        <span class="font-medium text-gray-400">SKU:</span> {{
                                            productDetail?.has_variant ? selectedVariant?.sku : productDetail?.sku }}
                                    </p>
                                    <div v-if="stockText" class="flex items-center gap-1.5">
                                        <span :class="isOutOfStock ? 'bg-red-500' : 'bg-green-500'"
                                            class="h-2 w-2 rounded-full shadow-sm"></span>
                                        <p class="text-sm font-semibold"
                                            :class="isOutOfStock ? 'text-red-600' : 'text-green-700'">
                                            {{ stockText }}
                                        </p>
                                    </div>
                                </div>
                            </header>

                            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <div class="flex flex-col">
                                    <span class="text-3xl font-bold text-gray-900 tracking-tight">Tk {{ currentPrice
                                        }}</span>
                                    <span v-if="unitPrice != null"
                                        class="text-sm text-gray-400 line-through decoration-red-400/50">
                                        Tk {{ unitPrice }}
                                    </span>
                                </div>
                                <div v-if="saveAmount" class="ml-auto">
                                    <span
                                        class="bg-primary-600 text-white text-[11px] font-bold px-3 py-1.5 rounded-full shadow-sm uppercase tracking-wide">
                                        Save {{ saveAmount }} BDT
                                    </span>
                                </div>
                            </div>

                            <div v-if="productDetail?.options?.length" class="space-y-6">
                                <div v-for="option in productDetail.options" :key="option.id"
                                    class="flex flex-col gap-3">
                                    <div class="flex justify-between items-end px-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                           Select {{ option.attribute_name }}
                                        </label>
                                        <span v-if="selectedOptions[option.attribute_name]"
                                            class="text-xs font-medium text-primary-600">
                                            Selected: {{ selectedOptions[option.attribute_name] }}
                                        </span>
                                    </div>

                                    <ul class="flex gap-3 flex-wrap">
                                        <li v-for="value in option.attribute_values" :key="value.value"
                                            @click="selectOption(option, value)" role="button" tabindex="0" :class="[
                                                'cursor-pointer select-none px-6 py-2.5 text-sm font-semibold rounded-xl border-2 transition-all duration-200 flex items-center gap-2.5',
                                                selectedOptions[option.attribute_name] === value.value
                                                    ? 'border-primary-600 bg-primary-50 text-primary-700 ring-4 ring-primary-600/5'
                                                    : 'border-gray-200 bg-white text-gray-600 hover:border-primary-300 hover:bg-gray-50'
                                            ]">

                                            <span v-if="value.color_code"
                                                class="h-4 w-4 rounded-full border border-black/10 shadow-inner"
                                                :style="{ backgroundColor: value.color_code }">
                                            </span>

                                            {{ value.value }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="w-full my-6">
                            <div class="flex flex-col items-start gap-4">
                                <div
                                    class="flex items-center justify-between gap-4 text-secondary-600 border border-primary-100 rounded-lg px-[10px] py-1">
                                    <button type="button" class="cursor-pointer" @click="decrementQty">
                                        <Minus class="h-5 w-5 md:h-6 md:w-6" />
                                    </button>
                                    <input
                                        type="number"
                                        min="1"
                                        v-model.number="quantity"
                                        class="font-medium text-sm md:text-base text-center w-12 md:w-16 border border-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-primary-100 focus:border-transparent [appearance:textfield] [&amp;::-webkit-outer-spin-button]:appearance-none [&amp;::-webkit-inner-spin-button]:appearance-none" />
                                    <button type="button" class="cursor-pointer" @click="incrementQty">
                                        <Plus class="h-5 w-5 md:h-6 md:w-6" />
                                    </button>
                                </div>
                                <template v-if="!isOutOfStock">
                                    <button
                                        class="flex-1 p-4 rounded-lg text-sm font-medium disabled:bg-black-disabled transition-colors duration-150 w-full bg-primary-600 border-2 border-primary-100 text-white disabled:text-white disabled:border-black-disabled cursor-pointer"
                                        :disabled="!canAddToCart"
                                        @click="handleAddToCart">
                                        {{ requiresVariant && !selectedVariant ? 'Select variant' : 'Add To Cart' }}
                                    </button>

                                    <button
                                        class="flex-1 p-4 bg-secondary-600 rounded-lg text-sm text-white font-medium disabled:bg-black-disabled transition-colors duration-150 w-full cursor-pointer"
                                        :disabled="!canAddToCart"
                                        @click="handleBuyNow">
                                        Buy Now
                                    </button>
                                </template>
                                <button v-else
                                    class="flex-1 p-4 rounded-lg text-sm font-medium w-full bg-danger-500 text-white cursor-not-allowed">
                                    Stock Out
                                </button>
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
                                        <div v-if="productDetail?.description" v-html="productDetail.description"></div>
                                        <p v-else>No description available.</p>
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
                                <div v-if="productDetail?.description" v-html="productDetail.description"></div>
                                <p v-else>No description available.</p>
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
                            <ProductCard v-for="product in relatedProducts" :key="product.id" :product="product" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
