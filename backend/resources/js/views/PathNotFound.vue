<script setup>
import { RouterLink } from 'vue-router';
</script>

<template>
    <div>
        <section class="flex min-h-[60vh] flex-col items-center justify-center px-6 text-center">
            <div class="bg-white p-6 rounded-lg max-w-3xl">
                <span class="text-sm font-bold tracking-[0.2em] text-indigo-500 uppercase">
                    404 Page Not Found
                </span>

                <h2 class="mt-4 text-2xl font-extralight tracking-tight text-slate-900 md:text-4xl">
                    Page Not Found
                </h2>

                <p class="mt-6 text-lg leading-7 text-slate-600 font-manrope">
                    The page you’re looking for doesn’t exist or has been moved.
                    Let's get you back on track.
                </p>

                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <RouterLink to="/"
                        class="rounded-full bg-primary-500 px-8 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:bg-primary-400 hover:scale-105 active:scale-95">
                        Go back home
                    </RouterLink>
                </div>
            </div>
        </section>
    </div>
</template>
