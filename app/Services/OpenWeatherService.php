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

//            https://api.openweathermap.org/data/2.5/weather?lat={lat}&lon={lon}&appid={API key}

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
}
