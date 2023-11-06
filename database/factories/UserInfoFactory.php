<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserInfo>
 */
class UserInfoFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'headline' => $this->faker->sentence,
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'additionalName' => $this->faker->firstName,
            'about' => $this->faker->paragraph,
            'profile_image' => $this->faker->imageUrl(),
            'cover_image' => $this->faker->imageUrl(),
            'user_id' => User::all()->random()->id,
        ];
    }
}
