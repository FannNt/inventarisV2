<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarService>
 */
class CarServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'kategori' => fake()->randomElement(['service_berkala','perbaikan','ganti_oli']),
            'total' => fake()->randomDigit(),
            'bengkel' => fake()->randomLetter(),
            'keterangan' => fake()->randomLetter(),
            'servie_at' => fake()->date()
        ];
    }
}
