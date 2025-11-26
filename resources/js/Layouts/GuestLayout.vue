<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 text-slate-900">
        <div class="mb-6">
            <Link :href="route('welcome')" class="flex flex-col items-center group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm ring-1 ring-slate-200 group-hover:shadow-md group-hover:ring-sky-200 transition-all duration-300 group-hover:-translate-y-1">
                    <span class="text-3xl">☁️</span>
                </div>
                <span class="mt-3 text-lg font-bold text-slate-700 tracking-tight group-hover:text-sky-600 transition-colors">InEvent Weather</span>
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl shadow-slate-200/50 overflow-hidden sm:rounded-[2rem] ring-1 ring-slate-100"
        >
            <slot />
        </div>
    </div>
</template>
