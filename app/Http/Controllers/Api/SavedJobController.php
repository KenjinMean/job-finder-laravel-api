<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class SavedJobController extends Controller {
    // private $savedJobsService;

    // public function __construct(SavedJobsService $savedJobsService) {
    //     $this->savedJobsService = $savedJobsService;
    // }
    public function index() {
        $user = JwtHelper::getUserFromToken();
        $savedJobs = $user->savedJobs()->get();

        return response()->json($savedJobs, 200);
    }

    public function store(Request $request, Job $job) {
        $user = JwtHelper::getUserFromToken();
        if ($user->savedJobs()->where('job_id', $job->id)->exists()) {
            return response()->json(['message' => 'Job already saved'], 409);
        }

        $user->savedJobs()->attach($job->id);

        return response()->json(['message' => 'Job saved successfully'], 200);
    }

    public function delete(Request $request, Job $job) {
        $user = JwtHelper::getUserFromToken();
        $user->savedJobs()->detach($job->id);

        return response()->json(['message' => 'Job unsaved successfully'], 200);
    }
}
