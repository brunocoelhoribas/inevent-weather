<?php

namespace App\Services;

use App\DTOs\WeatherDTO;

class WeatherAdvisor {
    public static function generateAdvice(WeatherDTO $weather): string {
        $advice = [];

        if ($weather->temperature < 10) {
            $advice[] = "It's freezing outside! Definitely wear a heavy coat.";
        } elseif ($weather->temperature < 18) {
            $advice[] = "It's a bit chilly. A sweater is recommended.";
        } elseif ($weather->temperature > 30) {
            $advice[] = "It's scorching hot! Stay hydrated.";
        } else {
            $advice[] = "The temperature is pleasant.";
        }

        if (str_contains(strtolower($weather->description), 'rain')) {
            $advice[] = "Don't forget your umbrella.";
        }

        return implode(" ", $advice);
    }
}
