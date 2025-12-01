<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Professor;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $professors = Professor::whereHas('department', function ($q) {
            $q->whereIn('department_code', ['DCE', 'DA']);
        })->get();

        foreach ($professors as $professor) {
            User::updateOrCreate(
                ['professor_id' => $professor->id],   // FIX: uses actual PK
                [
                    'name' => $professor->first_name . ' ' . $professor->last_name,
                    'email' => $professor->email
                        ?? strtolower(Str::slug($professor->first_name . $professor->last_name))
                        . rand(100, 999) . '@libacao-university.edu',
                    'password' => 'password123',
                    'is_admin' => true,
                    'professor_id' => $professor->id,    // FIX: use correct PK
                ]
            );
        }
    }
}