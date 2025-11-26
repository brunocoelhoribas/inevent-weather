<?php

namespace Database\Seeders;

use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Random\RandomException;

class SearchHistorySeeder extends Seeder {
    /**
     * @throws RandomException
     */
    public function run(): void {
        $user = User::firstOrCreate(
            ['email' => 'recruiter@inevent.com'],
            [
                'name' => 'InEvent Reviewer',
                'password' => bcrypt('password'),
            ]
        );

        $cities = ['London', 'New York', 'Tokyo', 'Paris', 'Berlin'];

        foreach ($cities as $city) {
            SearchHistory::create([
                'user_id' => $user->id,
                'city' => $city,
                'updated_at' => now()->subMinutes(random_int(1, 100))
            ]);
        }
    }
}
