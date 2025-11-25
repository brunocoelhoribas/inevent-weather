<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Models\SearchHistory;

class WeatherController extends Controller {
    protected OpenWeatherService $weatherService;

    public function __construct(OpenWeatherService $weatherService) {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request): RedirectResponse|InertiaResponse {
        if ($request->has(['lat', 'lon'])) {
            try {
                $cityName = $this->weatherService->getCityNameByCoords(
                    $request->float('lat'),
                    $request->float('lon')
                );

                return to_route('weather.index', ['city' => $cityName]);

            } catch (Exception $e) {
                return Inertia::render('Weather/Index', [
                    'error' => 'Could not determine location.',
                    'message' => $e->getMessage()
                ]);
            }
        }

        $weather = null;
        $forecast = null;
        $dailyForecast = null;
        $hourlyForecast = null;
        $error = null;

        if ($city = $request->input('city')) {
            try {
                $weather = $this->weatherService->getCurrentWeather($city);
                $forecast = $this->weatherService->getForecast($city);
                $dailyForecast = $forecast['daily'];
                $hourlyForecast = $forecast['hourly'];

                if (Auth::check()) {
                    SearchHistory::updateOrCreate(
                        ['user_id' => Auth::id(), 'city' => $weather->city],
                        ['updated_at' => now()]
                    );
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $recentSearches = Auth::check()
            ? SearchHistory::where('user_id', Auth::id())
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get()
            : [];

        return Inertia::render('Weather/Index', [
            'weather' => $weather,
            'forecast' => $dailyForecast,
            'hourly' => $hourlyForecast,
            'error' => $error,
            'filters' => $request->only(['city']),
            'recentSearches' => $recentSearches
        ]);
    }
}
