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
        $email = $user->email;

        return [
            'email' => $email,
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->city(),
            'zip_code' => $this->faker->countryCode(),
            'country' => $this->faker->country(),
            'user_id' => $user->id,
        ];
    }
}
