<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserInfo;
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
            'headline' => fake()->sentence,
            'firstName' => fake()->firstName,
            'lastName' => fake()->lastName,
            'additionalName' => fake()->firstName,
            'pronouns' => fake()->randomElement(['he/him', 'she/her', 'they/them']),
            'about' => fake()->paragraph,
            'location' => fake()->city,
            'profile_image' => fake()->imageUrl(),
            'cover_image' => fake()->imageUrl(),
            'user_id' => function () {
                $existingUserIds = UserInfo::pluck('user_id')->all();
                $nonExistingUserIds = User::whereNotIn('id', $existingUserIds)->pluck('id')->all();

                if (empty($nonExistingUserIds)) {
                    return null;
                }

                return $this->faker->unique()->randomElement($nonExistingUserIds);
            },
        ];
    }
}
