<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection {
        return JobResource::collection(Job::query()->orderBy('id', 'desc')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse {
        $job = Job::with('company')->findOrFail($id);
        return response()->json($job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
