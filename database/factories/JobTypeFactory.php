<?php

namespace Database\Factories;

use App\Models\JobType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobType>
 */
class JobTypeFactory extends Factory {
    protected $model = JobType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'job_type' => $this->faker->randomElement([
                "Full-Time",
                "Part-Time",
                "Remote",
                "On-Site",
                "Contract/Freelance",
                "Internship",
                "Temporary",
                "Freelance",
                "Commission-Based",
                "Seasonal",
                "Remote Part-Time",
                "Hybrid",
            ]),
        ];
    }
}
