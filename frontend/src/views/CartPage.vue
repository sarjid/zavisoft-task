<script setup>
import { Minus, Plus, Trash2 } from 'lucide-vue-next';
</script>

<template>

    <div
        class=" max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 px-4 sm:px-6 lg:px-8 py-8  rounded-[28px]">
        <div
            class="col-span-1 lg:col-span-7 order-2 lg:order-1 p-6 sm:p-8 bg-white/90 border border-primary-100 rounded-2xl shadow-xl shadow-black/5 backdrop-blur">
            <h1
                class="text-3xl md:text-[32px] font-bold tracking-tight text-black-1.2 line-clamp-2 mb-6">
                Place Order</h1>
            <h4 class="font-medium mb-3">Your details</h4>
            <form action="" class="space-y-6">
                <div class="space-y-1">
                    <div
                        class="border rounded-xl flex border-primary-100 bg-white/80 focus-within:border-primary-100 focus-within:ring-2 focus-within:ring-primary-200 transition">
                        <input
                            class="w-full outline-none bg-transparent p-3.5 text-sm placeholder:text-[#6B7280] placeholder:font-normal placeholder:text-[16px] caret-black-disabled rounded-xl"
                            placeholder="Name*">
                    </div>
                </div>


                <div class="space-y-1">
                    <div
                        class="border rounded-xl flex border-primary-100 bg-white/80 focus-within:border-primary-100 focus-within:ring-2 focus-within:ring-primary-200 transition">
                        <input
                            class="w-full outline-none bg-transparent p-3.5 text-sm placeholder:text-[#6B7280] placeholder:font-normal placeholder:text-[16px] caret-black-disabled rounded-xl"
                            placeholder="Phone*">
                    </div>
                </div>

                <div class="space-y-1">
                    <div
                        class="border rounded-xl flex border-primary-100 bg-white/80 focus-within:border-primary-100 focus-within:ring-2 focus-within:ring-primary-200 transition">
                        <input
                            class="w-full outline-none bg-transparent p-3.5 text-sm placeholder:text-[#6B7280] placeholder:font-normal placeholder:text-[16px] caret-black-disabled rounded-xl"
                            placeholder="Address*">
                    </div>
                </div>
                

               

                <div>
                    <h4 class="font-medium mt-2">Notes</h4>
                </div><textarea
                    class="h-[124px] rounded-[16px] w-full p-4 border border-primary-200  bg-white/80 focus:border-primary-100  focus:ring-primary-200 transition"
                    placeholder="write anything about your delivery" name="notes"></textarea>
                <div>

                </div>


                <div class="pt-2">
                    <div
                        class="px-4 py-6 bg-white/80 border border-primary-100 rounded-2xl text-sm grid gap-2">
                        <div class="flex items-center justify-between gap-2 font-medium tracking-[-0.64px]">
                            <div class="font-normal md:text-[16px] text-[#6B7280]">Subtotal</div>
                            <div
                                class="flex-1 w-full text-right md:text-[16px] font-semibold text-[#4B5563]">
                                1,140 BDT</div>
                        </div>


                        <div class="flex items-center justify-between gap-2 font-medium tracking-[-0.64px]">
                            <div class="font-normal md:text-[16px] text-[#6B7280]">Total </div>
                            <div
                                class="flex-1 w-full text-right md:text-[16px] font-semibold text-[#4B5563]">
                                1,250 BDT</div>
                        </div>
                    </div>
                </div>
                <div class=""><button type="submit"
                        class="w-full p-4 bg-primary-600 rounded-xl text-lg font-bold text-white disabled:bg-black-disabled transition-colors duration-150 flex items-center justify-center shadow-xl shadow-primary-600/20">সর্বমোট
                        1,250 BDT | Submit Order</button></div>
            </form>
        </div>
        <div class="col-span-1 order-1 lg:order-2 lg:col-span-5">
            <main class="lg:sticky lg:top-6">
                <div
                    class="p-6 sm:p-7 bg-white/90 rounded-2xl max-w-screen-md mx-auto border border-primary-100 shadow-xl shadow-black/5 backdrop-blur">
                    <h1
                        class="text-3xl md:text-[32px] font-bold tracking-tight text-black-1.2 line-clamp-2">
                        Cart</h1>
                    <div class="flex md:flex-row flex-col gap-6 mt-6">
                        <div class="flex flex-col w-full flex-1">
                            <h4 class="font-medium mb-4">
                                Selected items</h4>
                            <div class="overflow-hidden" style="height:276px;">
                                <div>
                                    <ul class="space-y-2 h-full">
                                        <li
                                            class="rounded-2xl overflow-hidden flex bg-white/90 sm:bg-blue-FG-2 border border-primary-100 shadow-sm hover:shadow-md transition">
                                            <div
                                                class="w-[101px] min-h-[101px] min-w-[101px] max-h-[101px] max-w-[101px] relative border-r border-black-4">
                                                <img alt="মাল্টি সেকশন প্লেট" loading="lazy" width="200" height="200"
                                                    decoding="async" data-nimg="1"
                                                    class="w-full rounded-lg object-cover" src="/products/1.jpg"
                                                    style="color:transparent;user-select:none;">
                                            </div>
                                            <div class="flex-1 p-4">
                                                <div class="flex flex-wrap gap-2 items-center justify-between w-full"><a
                                                        class="line-clamp-1 text-sm font-medium tracking-[-0.56px] min-w-1/2 text-primary-600"
                                                        href="/products/760118?c_id=H9mT9d8F6lhZj2Rzs-IBl">মাল্টি সেকশন
                                                        প্লেট</a><span
                                                        class="min-w-fit text-sm tracking-[-0.56px] mt-2">BDT
                                                        350</span></div>
                                                <div class="flex text-xs uppercase gap-1 flex-wrap mt-1">
                                                    <div
                                                        class="px-[8px] py-[3px] bg-primary-600/15 rounded text-primary-600">
                                                        ৪ পিসের সেট</div>
                                                </div>
                                                <div class="flex items-center justify-between mt-3">
                                                    <div
                                                        class="flex items-center justify-between gap-4 bg-transparent border border-primary-100 px-[10px] py-1">
                                                        <button>
                                                            <Minus class="text-primary-600 h-5 w-5 md:h-6 md:w-6" />
                                                        </button><span
                                                            class="text-primary-600 font-medium text-sm md:text-base">1</span><button>
                                                            <Plus class="text-primary-600 h-5 w-5 md:h-6 md:w-6" />
                                                        </button>
                                                    </div><button class="text-zinc-600">>
                                                            <Trash2 class="w-5 h-5" />
                                                        </button>
                                                </div>
                                            </div>
                                        </li>
                                        <li
                                            class="rounded-2xl overflow-hidden flex bg-white/90 sm:bg-blue-FG-2 border border-primary-100 shadow-sm hover:shadow-md transition">
                                            <div
                                                class="w-[101px] min-h-[101px] min-w-[101px] max-h-[101px] max-w-[101px] relative border-r border-black-4">
                                                <img alt="Multi Layer Shoe Rack" loading="lazy" width="200" height="200"
                                                    decoding="async" data-nimg="1"
                                                    class="w-full rounded-lg object-cover" src="/products/2.jpg"
                                                    style="color:transparent;user-select:none;">
                                            </div>
                                            <div class="flex-1 p-4">
                                                <div class="flex flex-wrap gap-2 items-center justify-between w-full"><a
                                                        class="line-clamp-1 text-sm font-medium tracking-[-0.56px] min-w-1/2 text-primary-600"
                                                        href="#p">Multi Layer
                                                        Shoe Rack</a><span
                                                        class="min-w-fit text-sm tracking-[-0.56px] mt-2">BDT
                                                        790</span></div>
                                                <div class="flex text-xs uppercase gap-1 flex-wrap mt-1">
                                                    <div
                                                        class="px-[8px] py-[3px] bg-primary-600/15 rounded text-primary-600">
                                                        ৫ লেয়ার</div>
                                                    <div
                                                        class="px-[8px] py-[3px] bg-primary-600/15 rounded text-primary-600">
                                                        কমলা</div>
                                                </div>
                                                <div class="flex items-center justify-between mt-3">
                                                    <div
                                                        class="flex items-center justify-between gap-4 bg-transparent border border-primary-100 px-[10px] py-1">
                                                        <button>
                                                            <Minus class="text-primary-600 h-5 w-5 md:h-6 md:w-6" />
                                                        </button><span
                                                            class="text-primary-600 font-medium text-sm md:text-base">2</span><button>
                                                            <Plus class="text-primary-600 h-5 w-5 md:h-6 md:w-6" />
                                                        </button>
                                                    </div><button class="text-zinc-600">
                                                            <Trash2 class="w-5 h-5" />
                                                        </button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div><button class="flex items-center gap-3 text-primary-600 text-sm my-4 sm:my-6">
                                <Plus class="h-3 w-3" />Add more items</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</template>


