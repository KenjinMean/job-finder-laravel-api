<?php

namespace App\Http\Controllers\Api;

use App\Models\JobType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobTypeController extends Controller {
    public function index() {
        $jobTypes = JobType::all();

        return response()->json($jobTypes);
    }
}
