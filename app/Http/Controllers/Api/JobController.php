<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Resources\JobPreliminaryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller {
  /**
   * Display a listing of the resource.
   */
  public function index(): AnonymousResourceCollection {
    return JobPreliminaryResource::collection(Job::with('company', 'category')
      ->orderBy('posted_at', 'desc')
      ->paginate(10));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreJobRequest $request) {
    $validated = $request->validated();

    try {
      $job = Job::create([
        'company_id' => $validated['company_id'],
        'category_id' => $validated['category_id'],
        'title' => $validated['title'],
        'location' => $validated['location'],
        'description' => $validated['description'],
        'requirements' => $validated['requirements'],
        'salary' => $validated['salary'],
        'posted_at' => now(),
      ]);

      return response()->json(['message' => 'Company created successfully', 'company' => $job]);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error creating job', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
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
