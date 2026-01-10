<script setup>
import { computed, ref, watch } from 'vue';
import { ShoppingCart, Zap } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import axiosInstance from '@/services/axiosService';
import { useCartStore } from '@/stores/cart';
import ProductVariantModal from '@/components/ProductVariantModal.vue';
import useNotify from '@/composables/useNotify';

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const router = useRouter();
const cartStore = useCartStore();
const notify = useNotify();

const modalOpen = ref(false);
const modalLoading = ref(false);
const modalProduct = ref(props.product);

const hasVariants = computed(() => {
  return Boolean(props.product?.has_variant || props.product?.options?.length);
});

const ensureModalProduct = async () => {
  if (!hasVariants.value) {
    return;
  }
  if (modalProduct.value?.options?.length && modalProduct.value?.variants?.length) {
    return;
  }
  if (!props.product?.slug) {
    return;
  }
  modalLoading.value = true;
  try {
    const response = await axiosInstance.get(`/products/slug/${props.product.slug}`);
    const detail = response?.data?.data?.product;
    if (detail) {
      modalProduct.value = detail;
    }
  } finally {
    modalLoading.value = false;
  }
};

const openModal = async () => {
  modalOpen.value = true;
  await ensureModalProduct();
};

const handleAddToCart = async () => {
  if (hasVariants.value) {
    await openModal();
    return;
  }
  cartStore.addItem({ product: props.product, quantity: 1 });
  notify.success('Added to cart');
};

const handleBuyNow = async () => {
  if (hasVariants.value) {
    await openModal();
    return;
  }
  cartStore.addItem({ product: props.product, quantity: 1 });
  notify.success('Added to cart');
  router.push('/cart');
};

const handleModalConfirm = ({ action, quantity, selectedVariant, selectedOptions }) => {
  cartStore.addItem({
    product: modalProduct.value || props.product,
    variant: selectedVariant,
    selectedOptions,
    quantity,
  });
  modalOpen.value = false;
  notify.success('Added to cart');
  if (action === 'buy') {
    router.push('/cart');
  }
};

watch(
  () => props.product,
  (nextProduct) => {
    modalProduct.value = nextProduct;
  }
);
</script>

<template>
  <article
    class="bg-white rounded-md shadow-sm border border-primary-50 hover:border-primary-600 overflow-hidden flex flex-col transition-all duration-200 hover:shadow-lg">
    <RouterLink
      :to="{ name: 'product-detail', params: { slug: product.slug } }"
      class="flex flex-col">
      <div role="button" class="relative">
        <div class="relative w-full h-48 sm:h-52 overflow-hidden">
          <img :alt="product.name || product.title" loading="lazy" decoding="async" data-nimg="fill"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105 p-2 rounded-xl hover:shadow-sm"
            :src="product.thumbnail || product.image"
            style="position: absolute; height: 100%; width: 100%; inset: 0; color: transparent; user-select: none;" />
          <div class="absolute bottom-3 right-2 md:top-3 md:right-3">
            <span class="bg-primary-100 text-primary-800 text-xs font-semibold px-2 py-1 rounded-md">
              <template v-if="product.discount_type === 'percent'">
                Save {{ product.discount ?? 0 }}%
              </template>
              <template v-else>
                Save Tk {{ product.discount ?? 0 }}
              </template>
            </span>
          </div>
        </div>
      </div>
      <div class="px-4 py-3 flex flex-col gap-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <span v-if="product.unit_price" class="text-danger-500 text-sm line-through">Tk {{ product.unit_price }}</span>
          <span class="text-lg sm:text-xl font-bold text-primary-900">
            Tk {{ product.current_price ?? product.unit_price ?? '' }}
          </span>
        </div>
        <div>
          <h3 class="text-center text-primary-900 font-semibold text-lg line-clamp-2">{{ product.name }}</h3>
        </div>
      </div>
    </RouterLink>
    <div class="px-4 pb-4 flex flex-col gap-2 mt-auto">
      <div class="w-full">
        <div class="flex flex-col gap-2">
          <button
            type="button"
            class="w-full bg-white border border-primary-600 hover:bg-primary-600 text-primary-700 hover:text-white font-medium py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors cursor-pointer"
            @click="handleAddToCart">
            <ShoppingCart class="h-4 w-4" /> Add To Cart
          </button>

          <button
            type="button"
            class="w-full bg-secondary-500 hover:bg-secondary-600 text-white border border-secondary-500 font-medium py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors cursor-pointer"
            @click="handleBuyNow">
            <Zap class="h-4 w-4" /> Buy Now
          </button>
        </div>
      </div>
    </div>
    <ProductVariantModal
      :open="modalOpen"
      :product="modalProduct || product"
      :loading="modalLoading"
      @close="modalOpen = false"
      @confirm="handleModalConfirm" />
  </article>
</template>
