<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DocumentType;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'summary' => $this->faker->paragraph,
            'document_type_id' => DocumentType::inRandomOrder()->value('id'),
            'created_by' => User::inRandomOrder()->value('id'),
            'issued_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
