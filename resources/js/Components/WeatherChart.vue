<script setup>
import {computed} from 'vue';
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
import {Line} from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

const props = defineProps({
    forecast: Array
});

const chartData = computed(() => {
    return {
        labels: props.forecast.map(day => {
            return new Date(day.date).toLocaleDateString('en-US', {weekday: 'short'});
        }),
        datasets: [
            {
                label: 'Temperature (°C)',
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
                    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
                    return gradient;
                },
                borderColor: '#3B82F6',
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3B82F6',
                pointRadius: 6,
                pointHoverRadius: 8,
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
    plugins: {
        legend: {display: false},
        tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            padding: 12,
            titleFont: {size: 14},
            bodyFont: {size: 14, weight: 'bold'}
        }
    },
    scales: {
        y: {
            display: false,
            grid: {display: false}
        },
        x: {
            grid: {display: false, drawBorder: false},
            ticks: {color: '#9CA3AF'}
        }
    }
};
</script>

<template>
    <div class="w-full h-64">
        <Line :data="chartData" :options="chartOptions"/>
    </div>
</template>
