<?php

namespace App\Services;

use App\Models\Job;
use App\Http\Resources\JobResource;
use App\Http\Resources\JobDetailsResource;
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

  /* SHOW JOB USING ID*/
  // public function showJob($jobId) {
  //   // return Job::with('company', 'skills', 'jobTypes')->findOrFail($jobId);

  //   $job = Job::with('company', 'skills', 'jobTypes')->findOrFail($jobId);
  //   return new JobDetailsResource($job);
  // }

  /* SHOW JOB USING SLUG*/
  public function showJob($jobSlug) {
    $job = Job::with('company', 'skills', 'jobTypes')
      ->where('slug', $jobSlug)
      ->first();

    if (!$job) {
      return response()->json(['message' => 'Job not found'], 404);
    }

    return new JobDetailsResource($job);
  }

  public function updateJob($validatedRequest, $job): void {
    $job->update($validatedRequest);
  }

  public function deleteJob($job) {
    $job->delete();
  }

  // GET paginated job
  public function getPaginatedJobsWithDetails() {
    return JobPreliminaryResource::collection(Job::with('company', 'skills', 'jobTypes')
      ->orderBy('created_at', 'desc')
      ->paginate(10));
  }

  // GET job per page
  public function getJobPostings($page) {
    $perPage = 10;
    $jobs = Job::with('company', 'skills', 'jobTypes')
      ->orderBy('created_at', 'desc')
      ->skip(($page - 1) * $perPage)
      ->take($perPage)
      ->get();

    return JobPreliminaryResource::collection($jobs);
  }

  public function searchJobs($keyword) {
    return JobPreliminaryResource::collection(Job::where('title', 'like', "%$keyword%")
      ->with('company', 'skills', 'jobTypes')
      ->orderBy('created_at', 'desc')
      ->paginate(10));
    //throw a queryException:->orderBy('none_existing_column', 'desc')
  }

  public function searchJobsSuggestions($keyword) {
    if (empty($keyword)) {
      return response()->json([
        'suggestions' => [],
      ]);
    }

    $suggestions = Job::where('title', 'like', "%$keyword%")
      ->distinct()
      ->pluck('title')
      ->take(10);

    return response()->json([
      'suggestions' => $suggestions,
    ]);
  }

  public function filterJobs($request) {
    $query = Job::query();

    if ($request->has('query')) {
      $queryText = $request->input('query');
      $query->where('title', 'like', '%' . $queryText . '%');
    }

    if ($request->has('job_type')) {
      $jobTypeNames = explode(',', $request->input('job_type'));
      $query->whereHas('jobTypes', function ($subQuery) use ($jobTypeNames) {
        $subQuery->whereIn('job_type', $jobTypeNames);
      });
    }

    if ($request->has('min_salary')) {
      $minSalary = $request->input('min_salary');
      $query->where('salary', '>=', $minSalary);
    }

    if ($request->has('max_salary')) {
      $maxSalary = $request->input('max_salary');
      $query->where('salary', '<=', $maxSalary);
    }

    $jobs = $query->get()->load('skills', 'company', 'jobTypes');

    $formattedJobs = JobPreliminaryResource::collection($jobs);

    return response()->json($formattedJobs);
  }

  public function updateJobSkills($skills, $job) {
    $job->skills()->sync($skills);
  }

  public function updateJobType($jobTypes, $job) {
    $job->jobTypes()->sync($jobTypes);
  }
}
