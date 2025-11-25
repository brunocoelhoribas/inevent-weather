<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    weather: Object,
    error: String,
    filters: Object
});

const form = useForm({
    city: props.filters.city || '',
});

const search = () => {
    form.get(route('weather.index'));
};
</script>

<template>
    <Head title="Weather Forecast" />

    <AuthenticatedLayout>
        <div class="py-12 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-10 text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-6 tracking-tight">
                        Check the Weather
                    </h2>
                    <form @submit.prevent="search" class="relative max-w-lg mx-auto">
                        <input
                            v-model="form.city"
                            type="text"
                            placeholder="Enter city name (e.g. London, New York)"
                            class="w-full pl-6 pr-32 py-4 rounded-full border-none shadow-lg focus:ring-2 focus:ring-blue-500 text-gray-700 bg-white dark:bg-gray-800 dark:text-white transition-all"
                        >
                        <button
                            type="submit"
                            class="absolute right-2 top-2 bottom-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-full px-6 transition-colors shadow-md"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Searching...</span>
                            <span v-else>Search</span>
                        </button>
                    </form>
                </div>

                <div v-if="error" class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 flex items-center gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ error }}</span>
                </div>

                <div v-if="weather" class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">

                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-8 text-white text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-x-10 -translate-y-10"></div>
                        <div class="absolute bottom-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full translate-x-10 translate-y-10"></div>

                        <h3 class="text-4xl font-bold tracking-wide">{{ weather.city }}</h3>
                        <div class="flex flex-col items-center justify-center mt-4">
                            <img :src="weather.icon" :alt="weather.description" class="w-32 h-32 drop-shadow-lg filter">
                            <div class="text-6xl font-bold mt-[-10px]">{{ Math.round(weather.temperature) }}°</div>
                            <p class="text-xl capitalize opacity-90 font-medium mt-2">{{ weather.description }}</p>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

                            <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Feels Like</span>
                                <span class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ Math.round(weather.feelsLike) }}°</span>
                            </div>

                            <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Min / Max</span>
                                <span class="text-xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ Math.round(weather.tempMin) }}° / {{ Math.round(weather.tempMax) }}°
                                </span>
                            </div>

                            <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Humidity</span>
                                <span class="text-xl font-bold text-blue-500 mt-1">{{ weather.humidity }}%</span>
                            </div>

                            <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Wind</span>
                                <span class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ weather.windSpeed }} km/h</span>
                            </div>

                            <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
                                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Pressure</span>
                                <span class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ weather.pressure }} hPa</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div v-if="!weather && !error" class="text-center mt-20 opacity-50">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Start by searching for a city above.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
