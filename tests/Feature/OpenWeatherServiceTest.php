<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\OpenWeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class OpenWeatherServiceTest extends TestCase {
    public function testFetchesCurrentWeather(): void {
        Http::fake(static function ($request) {
            $url = $request->url();

            if (str_contains($url, 'air_pollution')) {
                return Http::response([
                    'list' => [
                        ['main' => ['aqi' => 3]]
                    ]
                ]);
            }

            return Http::response([
                'name' => 'Navegantes',
                'main' => [
                    'temp' => 20.0,
                    'feels_like' => 19.5,
                    'temp_min' => 18.0,
                    'temp_max' => 22.0,
                    'humidity' => 80,
                    'pressure' => 1012
                ],
                'weather' => [
                    ['description' => 'light rain', 'icon' => '10d']
                ],
                'wind' => ['speed' => 5.0],
                'coord' => ['lat' => -26.89, 'lon' => -48.65],
                'visibility' => 10000,
                'dt' => 1630000000,
                'timezone' => -10800,
                'sys' => [
                    'sunrise' => 1630000000,
                    'sunset' => 1630040000
                ]
            ]);
        });

        Cache::flush();

        $service = new OpenWeatherService();
        $dto = $service->getCurrentWeather('Navegantes');

        $this->assertEquals('Navegantes', $dto->city);
        $this->assertEquals(3, $dto->aqi);
        $this->assertStringContainsString('umbrella', $dto->advice);
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
