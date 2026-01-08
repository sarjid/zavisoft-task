<script setup>

import { FormLabel, PlainTextInput } from '@/components/form';
import PrimaryButton from '@/components/button/PrimaryButton.vue';
</script>

<template>
    <section class="flex min-h-screen items-center justify-center px-6">
        <div class="w-full max-w-xl">
            <div class="rounded-3xl border border-primary-100 bg-white p-8 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    Admin Panel
                </p>
                <h1 class="mb-6 text-3xl font-extrabold text-slate-900">Login</h1>
                <div class="space-y-4">
                    <FormLabel>
                        Email Address
                        <PlainTextInput type="email" placeholder="email address"/>
                    </FormLabel>
                      <FormLabel>
                        Password
                        <PlainTextInput type="password" placeholder="password"/>
                    </FormLabel>
                    <PrimaryButton type="submit">
                        Login
                    </PrimaryButton>

                </div>
            </div>
        </div>
    </section>
</template>
