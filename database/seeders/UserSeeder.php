<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Professor;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $professors = Professor::whereHas('department', function ($q) {
            $q->whereIn('department_code', ['DCE', 'DAS', 'DTE']);
        })->get();

        foreach ($professors as $professor) {
            $user = User::updateOrCreate(
                ['professor_id' => $professor->professor_id],
                [
                    'name' => $professor->first_name . ' ' . $professor->last_name,
                    'email' => $professor->email ?? strtolower($professor->first_name) . '@example.com',
                    'password' => 'password123',
                    'is_admin' => true,
                    'professor_id' => $professor->professor_id,
                ]
            );
        }

    }
}