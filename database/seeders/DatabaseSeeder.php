<?php

namespace Database\Seeders;

use App\Enums\UserProfile;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@fiesc.com',
            'password' => Hash::make('12345678'),
            'profile' => UserProfile::ADMINISTRADOR,
        ]);

        $attendant = User::create([
            'name' => 'Atendente João',
            'email' => 'joao@fiesc.com',
            'password' => Hash::make('12345678'),
            'profile' => UserProfile::ATENDENTE,
        ]);

        $attendant2 = User::create([
            'name' => 'Atendente Maria',
            'email' => 'maria@fiesc.com',
            'password' => Hash::make('12345678'),
            'profile' => UserProfile::ATENDENTE,
        ]);

        Availability::create([
            'user_id' => $attendant->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'is_active' => true,
        ]);

        Availability::create([
            'user_id' => $attendant->id,
            'day_of_week' => 1,
            'start_time' => '14:00',
            'end_time' => '18:00',
            'is_active' => true,
        ]);

        Availability::create([
            'user_id' => $attendant->id,
            'day_of_week' => 2,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'is_active' => true,
        ]);

        Availability::create([
            'user_id' => $attendant2->id,
            'day_of_week' => 1,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_active' => true,
        ]);
    }
}
