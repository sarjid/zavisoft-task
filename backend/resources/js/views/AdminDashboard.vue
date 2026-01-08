<script setup>
import { Calendar, CreditCard, Users } from 'lucide-vue-next';
</script>


<template>
    <section class="space-y-6 px-6 py-6">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-semibold text-slate-900">Hello, Zavishop Bangladesh!</h2>
            <p class="text-sm text-slate-500">Here is a quick overview of your store performance today.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                    <CreditCard class="h-6 w-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Sales Today</p>
                    <p class="text-lg font-semibold text-slate-900">BDT 0</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                    <Calendar class="h-6 w-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Sales Monthly</p>
                    <p class="text-lg font-semibold text-slate-900">BDT 13734</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                    <Users class="h-6 w-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Website Visits</p>
                    <p class="text-lg font-semibold text-slate-900">12,468</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Last 30 days report</h3>
                    <p class="text-sm text-slate-500">Sales trend overview across recent weeks.</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <span class="h-2 w-2 rounded-full bg-primary-500"></span>
                    Sales
                </div>
            </div>
            <div
                class="mt-6 rounded-xl border border-dashed border-slate-200 bg-gradient-to-b from-white to-primary-50 p-4">
                <svg class="h-52 w-full" viewBox="0 0 800 220" fill="none">
                    <path
                        d="M20 180C80 80 140 40 200 120C260 200 320 60 380 80C440 100 500 20 560 90C620 160 680 60 740 40"
                        stroke="var(--color-primary-500)" stroke-width="4" />
                    <circle cx="80" cy="80" r="5" fill="var(--color-primary-600)" />
                    <circle cx="200" cy="120" r="5" fill="var(--color-primary-600)" />
                    <circle cx="320" cy="60" r="5" fill="var(--color-primary-600)" />
                    <circle cx="440" cy="100" r="5" fill="var(--color-primary-600)" />
                    <circle cx="560" cy="90" r="5" fill="var(--color-primary-600)" />
                    <circle cx="680" cy="60" r="5" fill="var(--color-primary-600)" />
                    <circle cx="740" cy="40" r="5" fill="var(--color-primary-600)" />
                </svg>
            </div>
        </div>

    </section>
</template>

