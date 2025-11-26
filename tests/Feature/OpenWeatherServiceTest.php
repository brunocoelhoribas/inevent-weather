<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\OpenWeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class OpenWeatherServiceTest extends TestCase {
    public function testItFetchesWeatherDataAndReturnsValidDto(): void {
        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'name' => 'Navegantes',
                'main' => [
                    'temp' => 25.5,
                    'feels_like' => 27.0,
                    'temp_min' => 24.0,
                    'temp_max' => 26.0,
                    'humidity' => 70,
                    'pressure' => 1012
                ],
                'weather' => [
                    ['description' => 'scattered clouds', 'icon' => '03d']
                ],
                'wind' => ['speed' => 5.5],
                'coord' => ['lat' => -26.89, 'lon' => -48.65],
                'visibility' => 10000,
                'dt' => 1630000000,
                'timezone' => -10800,
                'sys' => ['sunrise' => 1630000000, 'sunset' => 1630040000]
            ]),

            '*/air_pollution*' => Http::response([
                'list' => [
                    ['main' => ['aqi' => 3]]
                ]
            ])
        ]);

        Cache::flush();

        $service = new OpenWeatherService();
        $result = $service->getCurrentWeather('Navegantes');

        $this->assertEquals('Navegantes', $result->city);
        $this->assertEquals(25.5, $result->temperature);
        $this->assertEquals(3, $result->aqi);
        $this->assertNotEmpty($result->advice);
    }

    public function testThrowsExceptionIfCityNotFound(): void {
        Http::fake([
            '*' => Http::response([], 404),
        ]);

        Cache::flush();

        $this->expectException(Exception::class);

        $service = new OpenWeatherService();
        $service->getCurrentWeather('CidadeInexistente');
    }
}
