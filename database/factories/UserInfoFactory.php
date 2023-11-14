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
            'user_id' => User::all()->random()->id,
            'headline' => $this->faker->sentence,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'additional_name' => $this->faker->firstName,
            'about' => $this->faker->paragraph,
            'profile_image' => $this->faker->imageUrl(),
            'cover_image' => $this->faker->imageUrl(),
            'resume' => $this->faker->url(),
            'birth_date' => $this->faker->date(),
        ];
    }
}
