<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use App\Models\UserWorkExperience;

class SkillUserWorkExperienceSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $userWorkExperiences = UserWorkExperience::all();
        $skills = Skill::inRandomOrder()->get();

        foreach ($userWorkExperiences as $userWorkExperience) {
            // Generate a random number between 3 and 7 for the number of skills to attach
            $numberOfSkills = rand(2, 4);

            // Shuffle the skills and take the first $numberOfSkills
            $randomSkills = $skills->shuffle()->take($numberOfSkills);

            // Attach the selected skills to the user work experience
            $userWorkExperience->skills()->attach($randomSkills);
        }
    }
}
