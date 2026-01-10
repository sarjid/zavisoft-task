<script setup>
const props = defineProps({
  category: {
    type: Object,
    required: true,
  },
  isActive: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(["select"]);
</script>

<template>

  <div
    class="pl-7 py-4 pr-5 border-t last:border-b border-primary-50 flex justify-between items-center cursor-pointer hover:bg-primary-50/60 transition-colors"
    :class="isActive ? 'bg-primary-50/60' : ''"
    @click="emit('select', category)">
    <p class="text-sm font-semibold text-primary-900">{{ category.name }}</p>
  </div>

</template>
