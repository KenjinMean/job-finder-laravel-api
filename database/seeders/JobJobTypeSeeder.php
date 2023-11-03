<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\JobType;

class JobJobTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $jobs = Job::all();
        $jobTypes = JobType::all();

        $jobs->each(function ($job) use ($jobTypes) {
            $randomJobTypes = $jobTypes->random(mt_rand(1, 3));

            $randomJobTypes->each(function ($jobType) use ($job) {
                $job->jobTypes()->attach($jobType, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        });
    }
}
