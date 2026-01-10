<script setup>
const props = defineProps({
  placeholder: {
    type: String,
    default: 'Search',
  },
  modelValue: {
    type: String,
    default: '',
  },
  rootClass: {
    type: String,
    default: '',
  },
  containerClass: {
    type: String,
    default: 'max-w-4xl mx-auto relative',
  },
  ringClass: {
    type: String,
    default: 'ring-gray-200 hover:ring-primary-200',
  },
  inputClass: {
    type: String,
    default: '',
  },
  buttonClass: {
    type: String,
    default: 'bg-gradient-to-r from-primary-500 to-primary-600',
  },
});

const emit = defineEmits(['update:modelValue', 'search']);

const handleInput = (event) => {
  emit('update:modelValue', event.target.value);
};

const handleSearch = () => {
  emit('search', props.modelValue);
};
</script>

<template>
  <div :class="rootClass">
    <div :class="containerClass">
      <div
        :class="['relative group bg-white/80 backdrop-blur-lg rounded-xl transition-all duration-300 ring-1', ringClass]">
        <input type="text" :placeholder="placeholder"
          :class="['w-full h-14 pl-6 pr-14 bg-transparent outline-none text-gray-900 placeholder-gray-400 transition-all duration-300', inputClass]"
          :value="modelValue"
          @input="handleInput"
          @keyup.enter="handleSearch">
        <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
          <div
            :class="['w-10 h-10 flex items-center justify-center rounded-lg transition-transform duration-200 hover:scale-105 cursor-pointer', buttonClass]"
            @click="handleSearch">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
