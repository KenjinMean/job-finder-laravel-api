<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Job;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // \App\Models\Job::factory(30)->create();
        // \App\Models\Company::factory(20)->create();
        // $jobs = Job::all();
        // $skills = Skill::all();

        // foreach ($jobs as $job) {
        //     $randomSkills = $skills->random(mt_rand(1, 3));

        //     foreach ($randomSkills as $skill) {
        //         DB::table('job_skill')->insert([
        //             'job_id' => $job->id,
        //             'skill_id' => $skill->id,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }
    }
}
