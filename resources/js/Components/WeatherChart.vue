<script setup>
import { computed } from 'vue';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js';
import { Line } from 'vue-chartjs';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

const props = defineProps({
    forecast: Array
});

const chartData = computed(() => {
    return {
        labels: props.forecast.map(day => new Date(day.date).toLocaleDateString('en-US', { weekday: 'short' })),
        datasets: [
            {
                label: 'Temp',
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(14, 165, 233, 0.2)');
                    gradient.addColorStop(1, 'rgba(14, 165, 233, 0.0)');
                    return gradient;
                },
                borderColor: '#0ea5e9',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0ea5e9',
                pointRadius: 5,
                pointHoverRadius: 7,
                data: props.forecast.map(day => Math.round(day.temperature)),
                fill: true,
                tension: 0.4
            }
        ]
    }
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    layout: { padding: { top: 20, bottom: 10, left: 10, right: 10 } },
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#0f172a',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 10,
            cornerRadius: 8,
            displayColors: false
        }
    },
    scales: {
        y: { display: false },
        x: {
            grid: { display: false },
            ticks: { color: '#64748b' }
        }
    }
};
</script>

<template>
    <div class="w-full h-64">
        <Line :data="chartData" :options="chartOptions" />
    </div>
</template>
