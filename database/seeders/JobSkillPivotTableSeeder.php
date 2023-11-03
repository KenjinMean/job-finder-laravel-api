<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class JobSkillPivotTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $jobs = Job::all();
        $skills = Skill::inRandomOrder()->get();

        foreach ($jobs as $job) {
            // Generate a random number between 3 and 7 for the number of skills to attach
            $numberOfSkills = rand(3, 7);

            // Shuffle the skills and take the first $numberOfSkills
            $randomSkills = $skills->shuffle()->take($numberOfSkills);

            // Attach the selected skills to the job
            $job->skills()->attach($randomSkills);
        }
    }
}
