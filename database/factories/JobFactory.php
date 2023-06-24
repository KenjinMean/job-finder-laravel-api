<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'title' => fake()->jobTitle,
            'company_id' => function () {
                return Company::inRandomOrder()->first()->id;
            },
            'category_id' => function () {
                return Category::inRandomOrder()->first()->id;
            },
            'location' => fake()->city,
            'description' => fake()->paragraph,
            'requirements' => fake()->paragraph,
            'salary' => fake()->randomNumber(5),
            'posted_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTime,
        ];
    }
}
