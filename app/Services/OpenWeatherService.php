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
        return Cache::remember("weather_{$city}", 600, function () use ($city) {

            $response = Http::withoutVerifying()->get("{$this->baseUrl}weather", [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'en'
            ]);

            if ($response->failed()) {
                throw new RuntimeException("City not found: $city");
            }

            $data = $response->json();
            $lat = $data['coord']['lat'];
            $lon = $data['coord']['lon'];

            $pollutionResponse = Http::withoutVerifying()->get("{$this->baseUrl}air_pollution", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey
            ]);

            $aqi = 0;
            if ($pollutionResponse->successful()) {
                $aqi = $pollutionResponse->json()['list'][0]['main']['aqi'];
            }

            $data['aqi'] = $aqi;

            return WeatherDTO::fromApi($data);
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

            $daily = array_filter($list, static fn($item) => str_contains($item['dt_txt'], '12:00:00'));
            $mappedDaily = array_map(static function ($item) use ($data) {
                $item['name'] = $data['city']['name'];
                return WeatherDTO::fromApi($item);
            }, $daily);

            $hourlyRaw = array_slice($list, 0, 8);
            $mappedHourly = array_map(static function ($item) use ($data) {
                $item['name'] = $data['city']['name'];
                return WeatherDTO::fromApi($item);
            }, $hourlyRaw);

            return [
                'daily' => array_values($mappedDaily),
                'hourly' => $mappedHourly
            ];
        });
    }

    public function getCityNameByCoords(float $lat, float $lon): string {
        $response = Http::withoutVerifying()->get("{$this->baseUrl}weather", [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
            'limit' => 1
        ]);

        if ($response->failed()) {
            throw new RuntimeException("The coordinates could not be located..");
        }

        return $response->json()['name'];
    }
}
