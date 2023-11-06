<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserWebsite>
 */
class UserWebsiteFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $websiteTypes = ['personal', 'company', 'blog', 'RSS Feed', 'portfolio', 'other'];

        return [
            'website_url' => $this->faker->url(),
            'website_type' => $this->faker->randomElement($websiteTypes),
            'user_id' => User::all()->random()->id,
        ];
    }
}
