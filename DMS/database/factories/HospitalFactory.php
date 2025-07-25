<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hospital>
 */
class HospitalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Hospital', 
            'branch' => $this->faker->city, 
            'type' => $this->faker->randomElement(['Hospital', 'Clinic', 'Diagnosis', 'Medical Center', 'Specialty']),
            'deleted_at' => null,  
        ];
    }
}
