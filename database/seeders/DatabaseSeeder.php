<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserContact;
use App\Models\UserEducation;
use App\Models\UserWorkExperience;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {

        /** Create sample user */
        \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        /** Create 5 dummy users */
        \App\Models\User::factory(5)->create();

        /** Generate UserInfo */
        User::all()->each(function ($user) {
            UserInfo::factory()->create(['user_id' => $user->id]);
        });

        /** Generate company size category */
        $this->call(CompanySizeCategorySeeder::class);

        /** Create 20 company */
        \App\Models\Company::factory(100)->create();

        /** Create 40 jobs */
        \App\Models\Job::factory(1000)->create();

        /** Create JobTypes */
        \App\Models\JobType::factory()->count(8)->create();

        /** Create WorkLocationType */
        \App\Models\WorkLocationType::factory(3)->create();

        /** Call Skills Table Seeder */
        $this->call(SkillsTableSeeder::class);

        $this->call(JobSkillPivotTableSeeder::class);

        $this->call(UserSkillPivotTableSeeder::class);

        $this->call(JobJobTypeSeeder::class);

        $this->call(WorkLocationTypeSeeder::class);

        /** Generate UserContact for each user */
        User::all()->each(function ($user) {
            UserContact::factory()->create(['user_id' => $user->id]);
        });

        /** Generate User Websites */
        User::all()->each(function ($user) {
            $websiteTypes = ['personal', 'company', 'blog', 'RSS Feed', 'portfolio', 'other'];

            // Generate a random number of websites (between 1 and 3)
            $numWebsites = rand(1, 3);

            // Create a list of unique website types
            $uniqueWebsiteTypes = $websiteTypes;

            // Create an array to store selected website types
            $selectedTypes = [];

            // Randomly select unique types for each website
            for ($i = 0; $i < $numWebsites; $i++) {
                // If there are no more unique types, shuffle and reset
                if (empty($uniqueWebsiteTypes)) {
                    $uniqueWebsiteTypes = $websiteTypes;
                    shuffle($uniqueWebsiteTypes);
                }

                // Select and remove a random type from the list
                $selectedType = array_splice($uniqueWebsiteTypes, array_rand($uniqueWebsiteTypes), 1)[0];

                $selectedTypes[] = $selectedType;
            }

            // Create a website for each selected type
            foreach ($selectedTypes as $type) {
                \App\Models\UserWebsite::factory()->create([
                    'user_id' => $user->id,
                    'website_type' => $type,
                ]);
            }
        });

        // create user education
        User::all()->each(function ($user) {
            // Generate a random number of educations (between 1 and 2)
            $numEducations = rand(1, 2);

            // Create and associate 1-2 education records for the user
            UserEducation::factory($numEducations)->create([
                'user_id' => $user->id,
            ]);
        });

        // create user experience
        User::all()->each(function ($user) {
            // Generate a random number of experience (between 1 and 2)
            $numExperience = rand(3, 5);

            // Create and associate 1-2 experience records for the user
            UserWorkExperience::factory($numExperience)->create([
                'user_id' => $user->id,
            ]);
        });

        $this->call(SkillUserWorkExperienceSeeder::class);
    }
}
