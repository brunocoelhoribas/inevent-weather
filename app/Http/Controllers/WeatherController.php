<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeatherController extends Controller {
    protected OpenWeatherService $weatherService;

    public function __construct(OpenWeatherService $weatherService) {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request): Response {
        $weather = null;
        $forecast = null;
        $error = null;

        if ($city = $request->input('city')) {
            try {
                $weather = $this->weatherService->getCurrentWeather($city);
                $forecast = $this->weatherService->getForecast($city);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return Inertia::render('Weather/Index', [
            'weather' => $weather,
            'forecast' => $forecast,
            'error' => $error,
            'filters' => $request->only(['city'])
        ]);
    }
}
