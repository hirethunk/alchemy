<?php

namespace Database\Seeders;

use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Retrospective;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user_data = collect([
            [
                'name' => 'John Rudolph Drexler',
                'email' => 'john@thunk.dev',
            ],
        ]);

        $user_data->each(function ($data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
            ]);
        });

        $teams = collect([
            [
                'name' => 'InterNACHI',
                'retro_cycle_in_days' => 14,
                'last_retro_date' => now()->subDays(5),
            ],
            [
                'name' => 'Laravel',
                'retro_cycle_in_days' => 14,
                'last_retro_date' => now()->subDays(11),
            ],
            [
                'name' => 'Thunk',
                'retro_cycle_in_days' => 14,
                'last_retro_date' => now()->subDays(100),
            ],
            [
                'name' => 'GlitchSecure',
                'retro_cycle_in_days' => 14,
                'last_retro_date' => now()->subDays(35),
            ],
        ]);

        $teams->each(function ($team) {
            $team_model = Team::create([
                'name' => $team['name'],
                'retro_cycle_in_days' => $team['retro_cycle_in_days'],
            ]);
            
            $team_model->users()->attach(User::first()->id);

            Retrospective::create([
                'team_id' => $team_model->id,
                'date' => $team['last_retro_date'],
            ]);
        });
    }
}
