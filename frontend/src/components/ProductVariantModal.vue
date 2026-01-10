<script setup>
import { computed, ref, watch } from 'vue';
import { X, Minus, Plus } from 'lucide-vue-next';

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  product: {
    type: Object,
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'confirm']);

const quantity = ref(1);
const selectedOptions = ref({});

const options = computed(() => props.product?.options || []);

const selectedVariant = computed(() => {
  if (!options.value.length) {
    return null;
  }
  const required = options.value.map((option) => option.attribute_name);
  if (required.some((name) => !selectedOptions.value[name])) {
    return null;
  }
  return (props.product?.variants || []).find((variant) =>
    required.every((name) =>
      variant.attributes?.some(
        (attribute) =>
          attribute.attribute_name === name &&
          attribute.attribute_value === selectedOptions.value[name]
      )
    )
  );
});

const currentPrice = computed(() => {
  return (
    selectedVariant.value?.current_price ??
    selectedVariant.value?.price ??
    props.product?.current_price ??
    props.product?.unit_price ??
    null
  );
});

const unitPrice = computed(() => props.product?.unit_price ?? null);

const saveAmount = computed(() => {
  const unit = Number(unitPrice.value);
  const current = Number(currentPrice.value);
  if (Number.isFinite(unit) && Number.isFinite(current)) {
    return Math.max(unit - current, 0);
  }
  return props.product?.discount ?? 0;
});

const isOutOfStock = computed(() => {
  if (selectedVariant.value) {
    return Number(selectedVariant.value.quantity ?? 0) <= 0;
  }
  const stock = props.product?.stock;
  if (typeof stock === 'number') {
    return stock <= 0;
  }
  const status = props.product?.stock_status;
  return status ? String(status).toLowerCase().includes('out') : false;
});

const canConfirm = computed(() => {
  if (isOutOfStock.value) {
    return false;
  }
  if (options.value.length && !selectedVariant.value) {
    return false;
  }
  return true;
});

const selectOption = (option, value) => {
  selectedOptions.value = {
    ...selectedOptions.value,
    [option.attribute_name]: value.value,
  };
};

const handleConfirm = (action) => {
  if (!canConfirm.value) {
    return;
  }
  emit('confirm', {
    action,
    quantity: quantity.value,
    selectedVariant: selectedVariant.value,
    selectedOptions: selectedOptions.value,
  });
};

const increment = () => {
  quantity.value += 1;
};

const decrement = () => {
  quantity.value = Math.max(1, quantity.value - 1);
};

watch(
  () => props.open,
  (open) => {
    if (open) {
      quantity.value = 1;
      selectedOptions.value = {};
    }
  }
);
</script>

<template>
  <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center px-4">
    <transition
      enter-active-class="transition-opacity duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0">
      <div
        v-if="open"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="emit('close')"></div>
    </transition>

    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-12"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-12">
      <div
        v-if="open"
        class="relative w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-[0_30px_60px_-35px_rgba(15,23,42,0.7)] border border-primary-100">
        <div class="flex items-start justify-between gap-4 p-6 border-b border-primary-50">
          <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-2xl bg-primary-50 flex items-center justify-center overflow-hidden">
              <img
                v-if="product?.thumbnail || product?.image"
                :src="product.thumbnail || product.image"
                :alt="product?.name || 'Product'"
                class="h-full w-full object-cover" />
              <div v-else class="text-primary-600 text-sm font-semibold">Image</div>
            </div>
            <div>
              <p class="text-xs uppercase tracking-[0.2em] text-primary-500 font-semibold">Select variant</p>
              <h2 class="text-lg font-semibold text-primary-900">{{ product?.name || 'Product' }}</h2>
              <div class="flex items-center gap-3 text-sm">
                <span class="font-semibold text-secondary-600">Tk {{ currentPrice ?? '--' }}</span>
                <span v-if="unitPrice != null" class="text-xs text-gray-400 line-through">Tk {{ unitPrice }}</span>
                <span v-if="saveAmount" class="text-[10px] uppercase font-bold text-white bg-primary-600 px-2 py-0.5 rounded-full">
                  Save {{ saveAmount }}
                </span>
              </div>
            </div>
          </div>
          <button
            type="button"
            class="h-9 w-9 rounded-full border border-primary-100 flex items-center justify-center text-primary-600 hover:bg-primary-50"
            @click="emit('close')">
            <X class="h-4 w-4" />
          </button>
        </div>

        <div class="p-6 space-y-6">
          <div v-if="loading" class="space-y-4 animate-pulse">
            <div class="h-4 w-32 rounded bg-primary-100"></div>
            <div class="h-10 w-full rounded bg-primary-100"></div>
            <div class="h-4 w-28 rounded bg-primary-100"></div>
            <div class="h-10 w-full rounded bg-primary-100"></div>
          </div>
          <template v-else>
            <div v-if="options.length" class="space-y-5">
              <div v-for="option in options" :key="option.id" class="space-y-3">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-widest text-gray-500">
                  <span>{{ option.attribute_name }}</span>
                  <span v-if="selectedOptions[option.attribute_name]" class="text-primary-600">
                    {{ selectedOptions[option.attribute_name] }}
                  </span>
                </div>
                <div class="flex flex-wrap gap-2">
                  <button
                    v-for="value in option.attribute_values"
                    :key="value.value"
                    type="button"
                    class="px-4 py-2 rounded-xl border text-sm font-semibold transition-all duration-150"
                    :class="selectedOptions[option.attribute_name] === value.value
                      ? 'border-primary-600 bg-primary-50 text-primary-700 ring-4 ring-primary-600/10'
                      : 'border-gray-200 bg-white text-gray-600 hover:border-primary-300'"
                    @click="selectOption(option, value)">
                    <span
                      v-if="value.color_code"
                      class="h-3 w-3 rounded-full border border-black/10 inline-block mr-2 align-middle"
                      :style="{ backgroundColor: value.color_code }"></span>
                    {{ value.value }}
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="text-sm text-gray-600">
              No variant options found for this product.
            </div>

            <div class="flex items-center justify-between gap-4">
              <div
                class="flex items-center gap-4 border border-primary-100 rounded-lg px-3 py-2 text-primary-700">
                <button type="button" @click="decrement">
                  <Minus class="h-4 w-4" />
                </button>
                <input
                  type="number"
                  min="1"
                  v-model.number="quantity"
                  class="w-12 text-center text-sm font-semibold border border-gray-200 rounded-md py-1 outline-none" />
                <button type="button" @click="increment">
                  <Plus class="h-4 w-4" />
                </button>
              </div>
              <div
                class="text-xs font-semibold px-3 py-2 rounded-full"
                :class="isOutOfStock ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700'">
                {{ isOutOfStock ? 'Stock out' : 'Ready to ship' }}
              </div>
            </div>
          </template>
        </div>

        <div class="p-6 pt-0">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <button
              type="button"
              class="w-full border border-primary-600 text-primary-700 hover:bg-primary-600 hover:text-white font-semibold py-2.5 rounded-xl transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
              :disabled="loading || !canConfirm"
              @click="handleConfirm('add')">
              Add to cart
            </button>
            <button
              type="button"
              class="w-full bg-secondary-600 hover:bg-secondary-700 text-white font-semibold py-2.5 rounded-xl transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
              :disabled="loading || !canConfirm"
              @click="handleConfirm('buy')">
              Buy now
            </button>
          </div>
          <p v-if="options.length && !selectedVariant && !loading" class="mt-3 text-xs text-primary-600">
            Select all options to continue.
          </p>
        </div>
      </div>
    </transition>
  </div>
</template>
