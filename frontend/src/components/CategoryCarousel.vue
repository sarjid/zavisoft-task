<script setup>
const props = defineProps({
  category: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(["select"]);

</script>

<template>

  <div
    class="relative w-[120px] min-w-[120px] aspect-[4/5] rounded-2xl overflow-hidden bg-white shadow-sm border border-primary-50 cursor-pointer"
    @click="emit('select', category)">
    <img alt="image" loading="lazy" width="310" height="310" decoding="async" class="h-full w-full object-cover"
      :src="category.image">
    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/70 via-primary-800/30 to-transparent">
    </div>
    <div class="absolute inset-0 flex flex-col justify-end p-3">
      <h3 class="text-sm font-semibold text-white leading-tight">{{ category.name }}</h3>
    </div>
  </div>

</template>
