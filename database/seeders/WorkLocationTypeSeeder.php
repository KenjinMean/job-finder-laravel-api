<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\WorkLocationType;
use Illuminate\Database\Seeder;

class WorkLocationTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $jobs = Job::all();
        $workLocationTypes = WorkLocationType::inRandomOrder()->get();

        foreach ($jobs as $job) {
            // Generate a random number between 1 and the count of work location types
            $numberOfWorkLocationTypes = rand(1, $workLocationTypes->count());

            // Shuffle the work location types and take the first $numberOfWorkLocationTypes
            $randomWorkLocationTypes = $workLocationTypes->shuffle()->take($numberOfWorkLocationTypes);

            // Attach the selected work location types to the job
            $job->workLocationTypes()->attach($randomWorkLocationTypes);
        }
    }
}
