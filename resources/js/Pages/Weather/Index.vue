<script setup>
import {useForm, Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WeatherChart from '@/Components/WeatherChart.vue';
import {useGeolocation} from '@/Composables/useGeolocation';
import {getAqiInfo, getDayName, formatTime} from '@/Utils/weatherUtils';

const props = defineProps({
    weather: Object,
    forecast: Array,
    hourly: Array,
    error: String,
    filters: Object,
    recentSearches: Array
});

const form = useForm({
    city: props.filters.city || '',
});

const search = () => {
    form.get(route('weather.index'));
};

const searchCity = (cityName) => {
    form.city = cityName;
    search();
};

const {gettingLocation, getUserLocation} = useGeolocation(form, route);
</script>

<template>
    <Head title="Weather Forecast" />

    <AuthenticatedLayout>
        <div class="py-12 min-h-screen bg-slate-50 text-slate-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-10">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 mb-2">Weather Forecast</h2>
                    <p class="text-slate-500 mb-8">Check the latest conditions and forecast for your city.</p>

                    <form @submit.prevent="search" class="relative group max-w-xl">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>

                        <input
                            v-model="form.city"
                            type="text"
                            placeholder="Search city (e.g. Rio de Janeiro)..."
                            class="block w-full p-4 pl-12 text-sm text-slate-900 border-0 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 focus:ring-2 focus:ring-sky-400 transition-all placeholder:text-slate-400"
                        >

                        <button
                            type="button"
                            @click="getUserLocation"
                            class="absolute right-24 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-full p-2 transition-all"
                            title="Use my location"
                            :disabled="gettingLocation"
                        >
                            <svg v-if="!gettingLocation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <svg v-else class="animate-spin w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>

                        <button
                            type="submit"
                            class="absolute right-2 top-2 bottom-2 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-xl px-6 text-sm transition-colors shadow-sm shadow-sky-200"
                            :disabled="form.processing"
                        >
                            Search
                        </button>
                    </form>

                    <div v-if="recentSearches && recentSearches.length" class="mt-4 flex flex-wrap gap-2 items-center">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide mr-1">History</span>
                        <button
                            v-for="history in recentSearches"
                            :key="history.id"
                            @click="searchCity(history.city)"
                            class="px-3 py-1 bg-white ring-1 ring-slate-200 text-slate-600 rounded-lg text-xs hover:ring-sky-300 hover:text-sky-700 hover:shadow-sm transition-all"
                        >
                            {{ history.city }}
                        </button>
                    </div>
                </div>

                <div v-if="error" class="mb-8 p-4 bg-red-50 ring-1 ring-red-100 text-red-600 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="font-medium">{{ error }}</span>
                </div>

                <div v-if="weather" class="animate-fade-in space-y-6">

                    <div class="bg-gradient-to-br from-sky-50 via-white to-white rounded-[2rem] p-8 ring-1 ring-slate-100 shadow-xl shadow-sky-100/50 flex flex-col md:flex-row items-center md:justify-between gap-8 relative overflow-hidden">

                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-yellow-300/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="text-center md:text-left relative z-10">
                            <h1 class="text-5xl font-bold text-slate-900 mb-1 tracking-tight">{{ weather.city }}</h1>
                            <p class="text-sky-600 text-lg font-semibold capitalize bg-sky-50 inline-block px-3 py-1 rounded-full border border-sky-100">{{ weather.description }}</p>
                        </div>

                        <div class="flex items-center gap-4 relative z-10">
                            <img :src="weather.icon" :alt="weather.description" class="w-32 h-32 drop-shadow-md transform hover:scale-105 transition-transform duration-500">
                            <div class="text-7xl font-bold text-slate-900 tracking-tighter">{{ Math.round(weather.temperature) }}<span class="text-4xl text-sky-400 align-top font-light">°</span></div>
                        </div>

                        <div class="grid grid-cols-3 md:grid-cols-1 gap-6 md:gap-2 text-center md:text-right border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 md:pl-8 w-full md:w-auto relative z-10">
                            <div>
                                <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">Feels Like</div>
                                <div class="font-bold text-slate-700 text-lg">{{ Math.round(weather.feelsLike) }}°</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">Humidity</div>
                                <div class="font-bold text-slate-700 text-lg">{{ weather.humidity }}%</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">Wind</div>
                                <div class="font-bold text-slate-700 text-lg">{{ weather.windSpeed }} <span class="text-xs font-normal">km/h</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-2xl ring-1 ring-slate-100 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md hover:ring-sky-200 hover:-translate-y-1 group">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-sky-500 transition-colors">Min / Max</span>
                            <span class="text-lg font-bold text-slate-800 mt-2">{{ Math.round(weather.tempMin) }}° / {{ Math.round(weather.tempMax) }}°</span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl ring-1 ring-slate-100 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md hover:ring-sky-200 hover:-translate-y-1 group">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-sky-500 transition-colors">Pressure</span>
                            <span class="text-lg font-bold text-slate-800 mt-2">{{ weather.pressure }} <span class="text-sm font-normal text-slate-500">hPa</span></span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl ring-1 ring-slate-100 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md hover:ring-sky-200 hover:-translate-y-1 group">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-sky-500 transition-colors">Visibility</span>
                            <span class="text-lg font-bold text-slate-800 mt-2">
                                {{ weather.visibility ? (weather.visibility / 1000).toFixed(1) : '--' }} <span class="text-sm font-normal text-slate-500">km</span>
                            </span>
                        </div>

                        <div
                            class="p-5 rounded-2xl shadow-sm flex flex-col justify-between transition-all hover:shadow-md hover:-translate-y-1 border border-transparent"
                            :class="getAqiInfo(weather.aqi).bg"
                        >
                            <span class="text-xs font-bold uppercase opacity-80 tracking-wider" :class="getAqiInfo(weather.aqi).color.split(' ')[0]">Air Quality</span>
                            <div class="flex items-end justify-between mt-2">
                                <span class="text-xl font-black tracking-tight" :class="getAqiInfo(weather.aqi).color.split(' ')[0]">
                                    {{ getAqiInfo(weather.aqi).label }}
                                </span>
                                <span class="text-xs font-bold opacity-60 bg-white/50 px-2 py-1 rounded" :class="getAqiInfo(weather.aqi).color.split(' ')[0]">AQI {{ weather.aqi }}</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="hourly" class="py-4">
                        <h3 class="text-lg font-bold text-slate-900 mb-4 ml-1 flex items-center gap-2">
                            <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Hourly Forecast
                        </h3>
                        <div class="flex overflow-x-auto pb-4 gap-3 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
                            <div
                                v-for="(hour, index) in hourly"
                                :key="index"
                                class="min-w-[100px] bg-white p-4 rounded-2xl ring-1 ring-slate-100 flex flex-col items-center shrink-0 transition-all hover:ring-sky-300 hover:shadow-md group"
                            >
                                <span class="text-xs font-semibold text-slate-400 mb-3 group-hover:text-sky-600">{{ formatTime(hour.date) }}</span>
                                <img :src="hour.icon" class="w-10 h-10 mb-3 drop-shadow-sm group-hover:scale-110 transition-transform" alt="hour icon">
                                <span class="text-xl font-bold text-slate-800">{{ Math.round(hour.temperature) }}°</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="md:col-span-1 bg-white p-6 rounded-[2rem] ring-1 ring-slate-100 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                5-Day Outlook
                            </h3>
                            <div class="space-y-6">
                                <div v-for="(day, index) in forecast" :key="index" class="flex items-center justify-between group hover:bg-slate-50 p-2 rounded-xl transition-colors -mx-2">
                                    <span class="w-14 text-sm font-semibold text-slate-500 group-hover:text-sky-700">{{ getDayName(day.date) }}</span>
                                    <div class="flex items-center gap-3 flex-1 justify-center">
                                        <img :src="day.icon" class="w-8 h-8 drop-shadow-sm" alt="day icon">
                                        <span class="text-sm font-medium text-slate-500 truncate hidden sm:block">{{ day.description }}</span>
                                    </div>
                                    <span class="text-base font-bold text-slate-800">{{ Math.round(day.temperature) }}°</span>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 bg-white p-6 rounded-[2rem] ring-1 ring-slate-100 shadow-sm flex flex-col">
                            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                Temperature Trend
                            </h3>
                            <div class="flex-1 min-h-[250px]">
                                <WeatherChart :forecast="forecast" />
                            </div>
                        </div>
                    </div>

                </div>

                <div v-if="!weather && !error" class="text-center py-24">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-sky-50 mb-6 shadow-inner ring-1 ring-sky-100">
                        <svg class="w-12 h-12 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Ready to explore?</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">Enter a city above to see real-time weather data, air quality, and forecasts.</p>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
