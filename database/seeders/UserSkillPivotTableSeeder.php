<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class UserSkillPivotTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $users = User::all();
        $skills = Skill::inRandomOrder()->get();

        foreach ($users as $user) {
            // Generate a random number between 3 and 7 for the number of skills to attach
            $numberOfSkills = rand(3, 7);

            // Shuffle the skills and take the first $numberOfSkills
            $randomSkills = $skills->shuffle()->take($numberOfSkills);

            // Attach the selected skills to the job
            $user->skills()->attach($randomSkills);
        }
    }
}
