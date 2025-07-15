<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Client', 
            'branch' => $this->faker->city, 
            'address' => $this->faker->address, 
            'type' => $this->faker->randomElement(['Client', 'Clinic', 'Diagnosis', 'Medical Center', 'Specialty']),
            'deleted_at' => null,  
        ];
    }
}
