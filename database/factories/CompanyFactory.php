<?php

namespace Database\Factories;

use App\Models\CompanySizeCategory;
use App\Models\User;
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
            'company_logo' => "storage/company_logos/" . fake()->image(storage_path('app/public/company_logos'), 200, 200, null, false),
            'location' => fake()->city,
            'website' => fake()->url,
            'description' => fake()->paragraph,
            'industry' => fake()->jobTitle,
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'benefits' => fake()->paragraph(),
            'mission' => fake()->paragraph(),
            'vision' => fake()->paragraph(),
            'user_id' => function () {
                return User::inRandomOrder()->first()->id;
            },
            'company_size_id' => function () {
                return CompanySizeCategory::inRandomOrder()->first()->id;
            }
        ];
    }
}
