<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\DTOs\WeatherDTO;
use RuntimeException;

class OpenWeatherService {
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct() {
        $this->baseUrl = config('services.openweather.base_url');
        $this->apiKey = config('services.openweather.key');
    }

    public function getCurrentWeather(string $city): WeatherDTO {
        return Cache::remember("weather_$city", 600, function () use ($city) {

            $response = Http::get("{$this->baseUrl}weather", [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'en'
            ]);

            if ($response->failed()) {
                throw new RuntimeException("It was not possible to obtain data for the city: $city");
            }

            return WeatherDTO::fromApi($response->json());
        });
    }

    public function getForecast(string $city): array {
        return Cache::remember("forecast_$city", 1800, function () use ($city) {

            $response = Http::withoutVerifying()->get("{$this->baseUrl}forecast", [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'en'
            ]);

            if ($response->failed()) {
                return [];
            }

            $data = $response->json();
            $list = $data['list'];

            $dailyForecasts = array_filter($list, static function ($item) {
                return str_contains($item['dt_txt'], '12:00:00');
            });

            $mapped = array_map(static function ($item) use ($data) {
                $item['name'] = $data['city']['name'];
                return WeatherDTO::fromApi($item);
            }, $dailyForecasts);

            return array_values($mapped);
        });
    }
}
