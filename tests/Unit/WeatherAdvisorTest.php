<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\DTOs\WeatherDTO;
use App\Services\WeatherAdvisor;

class WeatherAdvisorTest extends TestCase {

    public function testAdvisesToWearHeavyCoatWhenFreezing(): void {
        $dto = new WeatherDTO(
            city: 'Test City',
            temperature: 5,
            feelsLike: 4,
            tempMin: 0,
            tempMax: 10,
            description: 'clear sky',
            icon: '01d',
            humidity: 50,
            windSpeed: 10,
            pressure: 1000,
            visibility: 10000,
            date: null,
            aqi: 1,
            advice: '',
            sunrise: '06:00',
            sunset: '18:00',
            timezone: 0
        );

        $advice = WeatherAdvisor::generateAdvice($dto);

        $this->assertStringContainsString('freezing', $advice);
        $this->assertStringContainsString('wear a heavy coat', $advice);
    }

    public function testWarnsAboutRain(): void {
        $dto = new WeatherDTO(
            city: 'London',
            temperature: 20,
            feelsLike: 20,
            tempMin: 15,
            tempMax: 25,
            description: 'light rain',
            icon: '10d',
            humidity: 80,
            windSpeed: 5,
            pressure: 1012,
            visibility: 8000,
            date: null,
            aqi: 1,
            advice: '',
            sunrise: '06:00',
            sunset: '18:00',
            timezone: 0
        );

        $advice = WeatherAdvisor::generateAdvice($dto);

        $this->assertStringContainsString("Don't forget your umbrella", $advice);
    }
}
