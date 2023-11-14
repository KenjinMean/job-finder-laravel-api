<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserWorkExperience>
 */
class UserWorkExperienceFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'user_id' => User::all()->random()->id,
            'company_name' => $this->faker->company(),
            'job_title' => $this->faker->jobTitle(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'location' => $this->faker->address(),
            'description' => $this->faker->paragraph(),
            'is_current' => $this->faker->boolean(),
        ];
    }
}
