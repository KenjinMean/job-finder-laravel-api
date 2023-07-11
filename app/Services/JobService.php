<?php

namespace App\Services;

use App\Models\Job;
use App\Http\Resources\JobResource;
use App\Http\Resources\JobPreliminaryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobService {
  public function index(): AnonymousResourceCollection {
    // try {
    //   // Simulating a faulty database query, comment to return companies
    //   throw new QueryException(
    //     'HY000',
    //     'SELECT * FROM non_existing_table',
    //     [], // Bindings
    //     new \Exception('Simulated database query error, uncomment me in App\Services\CompanyService'),
    //   );
    // } catch (QueryException $e) {
    //   throw $e;
    // }
    return JobResource::collection(Job::orderBy('created_at', 'desc')
      ->paginate(10));
  }

  public function createJob($validatedRequest): void {
    Job::create($validatedRequest);
  }

  public function showJob($jobId): Job {
    return Job::with('company')->findOrFail($jobId);
  }

  public function updateJob($validatedRequest, $job): void {
    $job->update($validatedRequest);
  }

  public function deleteJob($job) {
    $job->delete();
  }

  public function getPaginatedJobsWithDetails() {
    return JobPreliminaryResource::collection(Job::with('company', 'skills')
      ->orderBy('created_at', 'desc')
      ->paginate(10));
  }

  public function searchJobs($keyword) {
    return JobPreliminaryResource::collection(Job::where('title', 'like', "%$keyword%")
      ->with('company', 'skills')
      ->orderBy('created_at', 'desc')
      ->paginate(10));
    //throw a queryException ->orderBy('none_existing_column', 'desc')
  }

  public function updateJobSkills($skills, $job) {
    $job->skills()->sync($skills);
  }
}
