<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => fake()->company,
            'company_logo' => fake()->image(storage_path('app/public/company_logos'), 200, 200, null, false),
            'location' => fake()->city,
            'website' => fake()->url,
            'description' => fake()->paragraph,
            'industry' => fake()->jobTitle,
        ];
    }
}
