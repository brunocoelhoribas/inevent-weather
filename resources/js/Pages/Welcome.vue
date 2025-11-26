<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: false,
    },
    phpVersion: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-slate-50 text-slate-600 selection:bg-sky-200 selection:text-sky-900 font-sans">

        <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-gradient-to-b from-sky-50 to-transparent pointer-events-none"></div>

        <div class="relative w-full max-w-5xl mx-auto px-6">

            <header class="flex items-center justify-between py-10">
                <div class="flex items-center gap-2">
                    <div class="bg-white p-2 rounded-xl ring-1 ring-slate-100 shadow-sm">
                        <span class="text-2xl">☁️</span>
                    </div>
                    <span class="text-xl font-bold text-slate-900 tracking-tight">InEvent Weather</span>
                </div>

                <nav v-if="canLogin" class="flex gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors px-4 py-2"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="text-sm font-semibold text-white bg-slate-900 hover:bg-sky-600 px-5 py-2 rounded-xl transition-all shadow-lg shadow-slate-200 hover:shadow-sky-200"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="mt-16 sm:mt-24">
                <div class="text-center relative z-10">
                    <div class="inline-block mb-4 px-3 py-1 rounded-full bg-sky-50 text-sky-600 text-xs font-bold uppercase tracking-wider ring-1 ring-sky-100">
                        Technical Challenge InEvent, by Bruno Coelho
                    </div>

                    <h1 class="text-5xl sm:text-7xl font-bold tracking-tight text-slate-900 mb-6">
                        Smart Weather <br>
                        <span class="text-sky-500 relative">
                            Monitoring
                            <svg class="absolute w-full h-3 -bottom-1 left-0 text-sky-200 -z-10" viewBox="0 0 100 10" preserveAspectRatio="none">
                                <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" />
                            </svg>
                        </span>
                    </h1>

                    <p class="mt-6 text-lg sm:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                        A modern full-stack application integrating <strong>OpenWeather API</strong> with real-time data, geolocation, and interactive charts.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                        <Link :href="route('register')" class="bg-sky-600 hover:bg-sky-500 text-white text-lg font-bold py-4 px-8 rounded-2xl transition-all shadow-xl shadow-sky-200 hover:scale-105 hover:shadow-sky-300">
                            Create Free Account
                        </Link>
                        <Link :href="route('login')" class="bg-white hover:bg-slate-50 text-slate-700 text-lg font-bold py-4 px-8 rounded-2xl transition-all shadow-sm ring-1 ring-slate-200 hover:ring-slate-300">
                            Live Demo
                        </Link>
                    </div>
                </div>

                <footer class="py-10 border-slate-200 text-center">
                    <p class="text-sm text-slate-400">
                        Running on Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                    </p>
                </footer>
            </main>
        </div>
    </div>
</template>
