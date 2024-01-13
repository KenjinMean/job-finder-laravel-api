<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserContact>
 */
class UserContactFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'phone' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'province' => $this->faker->city(),
            'country' => $this->faker->country(),
            'zip_code' => $this->faker->postcode(),
            'birth_date' => $this->faker->date(),
        ];
    }
}
