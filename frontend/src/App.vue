<script setup>
import { RouterLink } from 'vue-router';
</script>

<template>
  <div>
    <header>
      <h1 class="text-3xl font-bold underline text-green-600">
        Hello world! Header
      </h1>
    </header>
    <div class="mx-auto flex">
      <nav class="flex items-center gap-4 text-sm font-semibold text-slate-700">
        <RouterLink to="/" class="rounded-full px-3 py-2 hover:bg-slate-100">Home</RouterLink>
        <RouterLink to="/cart" class="rounded-full px-3 py-2 hover:bg-slate-100">Cart</RouterLink>
      </nav>

    </div>

    <main>
      <RouterView />
    </main>


    <footer>
      <h1 class="text-3xl font-bold underline text-green-600">
        Hello world! footer
      </h1>
    </footer>
  </div>

</template>
