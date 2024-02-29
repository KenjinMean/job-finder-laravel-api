<?php

namespace Database\Factories;

use App\Models\JobType;
use App\Models\User;
use App\Models\WorkLocationType;
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
        $positions = ['Junior', 'Mid', 'Senior'];
        $workLocations = WorkLocationType::pluck('name')->toArray();
        $jobType = JobType::pluck('job_type')->toArray();

        return [
            'user_id' => User::all()->random()->id,
            'company_name' => $this->faker->company(),
            'job_title' => $this->faker->jobTitle(),
            'position' => $this->faker->randomElement($positions),
            'job_type' => $this->faker->randomElement($jobType),
            'work_location_type' => $this->faker->randomElement($workLocations),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'location' => $this->faker->address(),
            'is_current' => $this->faker->boolean(),
        ];
    }
}
