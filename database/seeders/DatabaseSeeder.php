<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Job;
use App\Models\User;
use App\Models\Skill;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Factories\JobSkillFactory;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        /** Create 5 users */
        \App\Models\User::factory(5)->create();

        // /** Create single user */
        \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        /** Generate UserInfo */
        User::all()->each(function ($user) {
            UserInfo::factory()->create(['user_id' => $user->id]);
        });

        // Generate company size category
        $this->call(CompanySizeCategorySeeder::class);

        // /** Create 20 company */
        \App\Models\Company::factory(50)->create();

        /** Create 40 jobs */
        \App\Models\Job::factory(200)->create();

        /** Create JobTypes */
        \App\Models\JobType::factory()->count(16)->create();

        /** Call Skills Table Seeder */
        $this->call(SkillsTableSeeder::class);

        $this->call(JobSkillPivotTableSeeder::class);

        $this->call(JobJobTypeSeeder::class);
    }
}
