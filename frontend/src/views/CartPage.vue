<script setup>
import { computed, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { Loader2, Minus, Plus, Trash2 } from 'lucide-vue-next';
import { storeToRefs } from 'pinia';
import { useCartStore } from '@/stores/cart';
import axiosInstance from '@/services/axiosService';
import FormLabel from '@/components/form/FormLabel.vue';
import PlainTextInput from '@/components/form/PlainTextInput.vue';
import useNotify from '@/composables/useNotify';

const cartStore = useCartStore();
const router = useRouter();
const notify = useNotify();
const { items, subtotal } = storeToRefs(cartStore);

const formattedSubtotal = computed(() => subtotal.value.toLocaleString());

const form = reactive({
    name: '',
    phone: '',
    address: '',
    notes: '',
});
const submitting = ref(false);
const submitError = ref('');

const canSubmit = computed(() => {
    return (
        items.value.length > 0 &&
        form.name.trim() &&
        form.phone.trim() &&
        form.address.trim() &&
        !submitting.value
    );
});

const resetForm = () => {
    form.name = '';
    form.phone = '';
    form.address = '';
    form.notes = '';
};

const handleSubmit = async () => {
    submitError.value = '';
    if (!items.value.length) {
        submitError.value = 'Your cart is empty.';
        notify.error(submitError.value);
        return;
    }
    if (!form.name.trim() || !form.phone.trim() || !form.address.trim()) {
        submitError.value = 'Please fill in all required fields.';
        notify.error(submitError.value);
        return;
    }
    submitting.value = true;
    try {
        const payload = {
            name: form.name,
            phone: form.phone,
            address: form.address,
            notes: form.notes,
            items: items.value.map((item) => ({
                product_id: item.productId,
                product_variation_id: item.variantId,
                quantity: item.quantity,
                options: item.options || [],
            })),
        };
        const response = await axiosInstance.post('/orders', payload);
        cartStore.clearCart();
        resetForm();
        const orderId = response?.data?.data?.order_id;
        notify.success('Order placed successfully');
        router.push({ name: 'order-success', query: orderId ? { orderId } : {} });
    } catch (error) {
        submitError.value = error?.response?.data?.message || 'Unable to submit order.';
        notify.error(submitError.value);
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <div
        class=" max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 px-4 sm:px-6 lg:px-8 py-8  rounded-[28px]">
        <div
            class="col-span-1 lg:col-span-7 order-2 lg:order-1 p-6 sm:p-8 bg-white/90 border border-primary-100 rounded-2xl shadow-xl shadow-black/5 backdrop-blur">
            <h1 class="text-3xl md:text-[32px] font-bold tracking-tight text-black-1.2 line-clamp-2 mb-6">
                Place Order</h1>
            <h4 class="font-medium mb-3">Your details</h4>
            <form class="space-y-6" @submit.prevent="handleSubmit">
                <div class="space-y-1">
                    <FormLabel>Name*</FormLabel>
                    <PlainTextInput v-model="form.name" placeholder="Name" />
                </div>


                <div class="space-y-1">
                    <FormLabel>Phone*</FormLabel>
                    <PlainTextInput v-model="form.phone" placeholder="Phone" />
                </div>

                <div class="space-y-1">
                    <FormLabel>Address*</FormLabel>
                    <PlainTextInput v-model="form.address" placeholder="Address" />
                </div>

                <div>
                    <FormLabel>Notes</FormLabel>
                </div><textarea
                    v-model="form.notes"
                    class="h-[124px] rounded-[16px] w-full p-4 border border-primary-200  bg-white/80 focus:border-primary-100  focus:ring-primary-200 transition"
                    placeholder="write anything about your delivery" name="notes"></textarea>
                <div>

                </div>


                <div class="pt-2">
                    <div class="px-4 py-6 bg-white/80 border border-primary-100 rounded-2xl text-sm grid gap-2">
                        <div class="flex items-center justify-between gap-2 font-medium tracking-[-0.64px]">
                            <div class="font-normal md:text-[16px] text-[#6B7280]">Subtotal</div>
                            <div class="flex-1 w-full text-right md:text-[16px] font-semibold text-[#4B5563]">
                                {{ formattedSubtotal }} BDT</div>
                        </div>


                        <div class="flex items-center justify-between gap-2 font-medium tracking-[-0.64px]">
                            <div class="font-normal md:text-[16px] text-[#6B7280]">Total </div>
                            <div class="flex-1 w-full text-right md:text-[16px] font-semibold text-[#4B5563]">
                                {{ formattedSubtotal }} BDT</div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="w-full p-4 bg-primary-600 rounded-xl text-lg font-bold text-white disabled:bg-black-disabled transition-colors duration-150 flex items-center justify-center shadow-xl shadow-primary-600/20 cursor-pointer"
                        :disabled="!canSubmit">
                        <Loader2 v-if="submitting" class="spinner h-5 w-5" />
                        {{ formattedSubtotal }} BDT | Submit Order
                    </button>
                    <p v-if="submitError" class="mt-3 text-sm text-red-500">{{ submitError }}</p>
                </div>
            </form>
        </div>
        <div class="col-span-1 order-1 lg:order-2 lg:col-span-5">
            <main class="lg:sticky lg:top-6">
                <div
                    class="p-6 sm:p-7 bg-white/90 rounded-2xl max-w-screen-md mx-auto border border-primary-100 shadow-xl shadow-black/5 backdrop-blur">
                    <h1 class="text-3xl md:text-[32px] font-bold tracking-tight text-black-1.2 line-clamp-2">
                        Cart</h1>
                    <div class="flex md:flex-row flex-col gap-6 mt-6">
                        <div class="flex flex-col w-full flex-1">
                            <h4 class="font-medium mb-4">
                                Selected items</h4>
                            <div class="overflow-y-auto" style="height:600px;">
                                <div v-if="items.length">
                                    <ul class="space-y-2 h-full">
                                        <li
                                            v-for="item in items"
                                            :key="item.key"
                                            class="rounded-2xl overflow-hidden flex bg-white/90 sm:bg-blue-FG-2 border border-primary-100 shadow-sm hover:shadow-md transition">
                                            <div
                                                class="w-[101px] min-h-[101px] min-w-[101px] max-h-[101px] max-w-[101px] relative border-r border-black-4 overflow-hidden">
                                                <img
                                                    :alt="item.name"
                                                    loading="lazy"
                                                    width="200"
                                                    height="200"
                                                    decoding="async"
                                                    class="w-full rounded-lg object-cover"
                                                    :src="item.image"
                                                    style="color:transparent;user-select:none;">
                                            </div>
                                            <div class="flex-1 p-4">
                                                <div class="flex flex-wrap gap-2 items-center justify-between w-full">
                                                    <RouterLink
                                                        v-if="item.slug"
                                                        class="line-clamp-1 text-sm font-medium tracking-[-0.56px] min-w-1/2 text-primary-600"
                                                        :to="{ name: 'product-detail', params: { slug: item.slug } }">
                                                        {{ item.name }}
                                                    </RouterLink>
                                                    <span v-else
                                                        class="line-clamp-1 text-sm font-medium tracking-[-0.56px] min-w-1/2 text-primary-600">
                                                        {{ item.name }}
                                                    </span>
                                                    <span class="min-w-fit text-sm tracking-[-0.56px] mt-2">
                                                        BDT {{ item.price }}
                                                    </span>
                                                </div>
                                                <div v-if="item.options?.length" class="flex text-xs uppercase gap-1 flex-wrap mt-1">
                                                    <div
                                                        v-for="option in item.options"
                                                        :key="option.name"
                                                        class="px-[8px] py-[3px] bg-primary-600/15 rounded text-primary-600">
                                                        {{ option.name }}: {{ option.value }}
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between mt-3">
                                                    <div
                                                        class="flex items-center justify-between gap-4 bg-transparent border border-primary-100 px-[10px] py-1">
                                                        <button type="button" @click="cartStore.decrement(item.key)">
                                                            <Minus class="text-primary-600 h-5 w-5 md:h-6 md:w-6" />
                                                        </button>
                                                        <span class="text-primary-600 font-medium text-sm md:text-base">
                                                            {{ item.quantity }}
                                                        </span>
                                                        <button type="button" @click="cartStore.increment(item.key)">
                                                            <Plus class="text-primary-600 h-5 w-5 md:h-6 md:w-6" />
                                                        </button>
                                                    </div>
                                                    <button type="button" class="text-zinc-600" @click="cartStore.removeItem(item.key)">
                                                        <Trash2 class="w-5 h-5" />
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div v-else class="h-full flex items-center justify-center text-sm text-gray-500">
                                    Your cart is empty.
                                </div>
                            </div>
                            <RouterLink to="/" class="flex items-center gap-3 text-primary-600 text-sm my-4 sm:my-6">
                                <Plus class="h-3 w-3" />Add more items
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</template>
