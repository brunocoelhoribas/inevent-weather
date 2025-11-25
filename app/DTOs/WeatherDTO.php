<?php

namespace App\DTOs;

use Carbon\Carbon;

class WeatherDTO {
    public function __construct(
        public string $city,
        public float  $temperature,
        public float  $feelsLike,
        public float  $tempMin,
        public float  $tempMax,
        public string $description,
        public string $icon,
        public int    $humidity,
        public float  $windSpeed,
        public int    $pressure,
        public int $visibility,
        public ?string $date = null,
        public int $aqi = 0,
        public string $sunrise,
        public string $sunset,
        public int $timezone
    ) {
    }

    public static function fromApi(array $data): self {
        $timezoneOffset = $data['timezone'] ?? 0;

        $formatTime = static fn($timestamp) => Carbon::createFromTimestamp($timestamp + $timezoneOffset)
            ->utc()
            ->format('H:i');

        return new self(
            city: $data['name'],
            temperature: $data['main']['temp'],
            feelsLike: $data['main']['feels_like'],
            tempMin: $data['main']['temp_min'],
            tempMax: $data['main']['temp_max'],
            description: $data['weather'][0]['description'],
            icon: "https://openweathermap.org/img/wn/{$data['weather'][0]['icon']}@4x.png",
            humidity: $data['main']['humidity'],
            windSpeed: $data['wind']['speed'],
            pressure: $data['main']['pressure'],
            visibility: $data['visibility'],
            date: $data['dt_txt'] ?? null,
            aqi: $data['aqi'] ?? 0,
            sunrise: isset($data['sys']['sunrise']) ? $formatTime($data['sys']['sunrise']) : '--:--',
            sunset: isset($data['sys']['sunset']) ? $formatTime($data['sys']['sunset']) : '--:--',
            timezone: $timezoneOffset
        );
    }
}
