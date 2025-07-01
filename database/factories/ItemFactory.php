<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid,
            'no_seri' => fake()->uuid,
            'name' => fake()->firstName,
            'ruangan_id' => random_int(1,10),
            'merk' => fake()->word,
            'type' => fake()->word,
            'tahun_pengadaan' => fake()->year,
            'expired_at' => fake()->date
        ];
    }
}
