<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use Carbon\Carbon;

class StudentFactory extends Factory
{
    public function definition()
    {
        // mixed Filipino + international given names and surnames
        $first = $this->faker->randomElement([
            'John Mark',
            'Mary Ann',
            'Robin James',
            'Andy Josh',
            'Emmanuel',
            'Mark',
            'Athena',
            'Kimmy',
            'Joshua',
            'Princess',
            'Carlo',
            'Renz',
            'Ethan',
            'Chloe',
            'Aaliyah',
            'Marcus',
            'Hannah',
            'Sophia',
            'Liam',
            'Olivia',
            'Noah',
            'Emma',
            'Ava',
            'Isabella',
            'Mia',
            'Charlotte',
            'Ira',
            'Jose',
            'Ana',
            'Luis',
            'Carmen',
            'Miguel',
            'Sofia',
            'Diego',
            'Isabel',
            'Gabriel',
            'Lucia',
            'Ethan',
            'Hannah',
            'Luna',
            'Leo',
            'Zoe',
            'Amelia',
            'Elijah',
            'Aria',
            'James',
            'Ella',
            'Ciara',
            'Kai',
            'Nina',
            'Jade'
        ]);
        $last = $this->faker->randomElement([
            'Dela Cruz',
            'Santos',
            'Bautista',
            'Dizon',
            'Reyes',
            'Gomez',
            'Lee',
            'Kim',
            'Johnson',
            'Martinez',
            'Garcia',
            'Tan',
            'Smith',
            'Brown',
            'Wilson',
            'Taylor',
            'Anderson',
            'Thomas',
            'Moore',
            'Jackson',
            'Nguyen',
            'Patel',
            'Khan',
            'Hernandez',
            'Lopez',
            'Gonzalez',
            'Rodriguez',
            'Martinez',
            'Davis',
            'Miller',
            'Wilson',
            'Moore',
            'Taylor',
            'Anderson',
            'Thomas',
            'Jackson',
            'White',
            'Harris',
            'Martin',
            'Thompson',
            'Garcia',
            'Martinez',
            'Robinson',
            'Clark'
        ]);
        $yearLevel = $this->faker->numberBetween(1, 4);
        $studentNumber = now()->format('Y') . '-' . $this->faker->unique()->numerify('5###');

        return [
            'student_number' => $studentNumber,
            'first_name' => explode(' ', $first)[0],
            'middle_name' => null,
            'last_name' => $last,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'birth_date' => $this->faker
                ->dateTimeBetween('-25 years', '-18 years')
                ->format('Y-m-d'),
            'email' => strtolower(Str::slug($first . $last))
                . rand(1000, 9999)
                . '@libacao.edu.ph',
            'phone_number' => '09' . $this->faker->numerify('########'),
            'address' => $this->faker->address(),
            'enrollment_date' => Carbon::parse(now()),
            'year_level' => $yearLevel,
            'status' => 'Enrolled',
        ];
    }
}
