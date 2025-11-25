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
    <Head title="Weather Challenge" />

    <div class="min-h-screen bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">☁️ InEvent Weather</h1>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6">
                    <div class="text-center py-16 lg:py-20">
                        <h2 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-5xl">
                            Smart Weather <span class="text-blue-500">Monitoring</span>
                        </h2>
                        <p class="mt-4 text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
                            System developed as part of the technical challenge for the Full Stack Developer role at InEvent.
                            Complete integration with OpenWeather API.
                        </p>
                        <div class="mt-8 flex justify-center gap-4">
                            <Link :href="route('register')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Create Free Account
                            </Link>
                            <Link :href="route('login')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">
                                Access System
                            </Link>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 mt-10">

                        <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-white/10 transition hover:shadow-md">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                Backend (Laravel 12)
                            </h3>
                            <ul class="mt-4 space-y-2 text-gray-600 dark:text-gray-400">
                                <li>• Service Layer Pattern for APIs</li>
                                <li>• Cache Driver (File/Redis) for performance</li>
                                <li>• DTOs for strict data typing</li>
                                <li>• Optimized HTTP Client</li>
                            </ul>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-white/10 transition hover:shadow-md">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                Frontend (Vue 3)
                            </h3>
                            <ul class="mt-4 space-y-2 text-gray-600 dark:text-gray-400">
                                <li>• Inertia.js (Modern Monolith)</li>
                                <li>• Tailwind CSS for styling</li>
                                <li>• Composition API (`script setup`)</li>
                                <li>• Responsive Layout</li>
                            </ul>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </footer>
            </div>
        </div>
    </div>
</template>
