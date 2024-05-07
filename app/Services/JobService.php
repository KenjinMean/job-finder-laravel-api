<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Skill;
use App\Http\Resources\JobDetailsResource;
use App\Http\Resources\JobPreliminaryResource;

class JobService {
  public function index($validatedRequest) {

    // Filters
    $title = $validatedRequest['title'] ?? null;
    $location = $validatedRequest['location'] ?? null;
    $companyName = $validatedRequest['company'] ?? null;
    $minSalary = $validatedRequest['min_salary'] ?? null;
    $maxSalary = $validatedRequest['max_salary'] ?? null;

    // Filters that is an array
    $skills = $validatedRequest['skills'] ?? [];
    $jobTypes = $validatedRequest['job_type'] ?? [];
    $workLocationTypes = $validatedRequest['work_location_type'] ?? [];

    // Relationship to Load 
    $load = $validatedRequest['load'] ?? []; // skills, company, jobTypes, workLocationTypes

    // Sorting Criteria
    $orderBy = $validatedRequest['order_by'] ?? 'created_at';
    $orderDirection = $validatedRequest['order_direction'] ?? 'desc';
    $perPage = $validatedRequest['per_page'] ?? 10;

    // base query
    $query = Job::query();

    // load relationship
    if (!empty($load)) {
      $query->with($load);
    }

    // Filter job postings by title
    if ($title) {
      $query->where('title', 'like', "%$title%");
    }

    // Filter job postings by company name
    if ($companyName) {
      $query->whereHas('company', function ($query) use ($companyName) {
        $query->where('name', 'like', "%$companyName%");
      });
    }

    // Filter job postings by job types
    if (!empty($jobTypes)) {
      $query->whereHas('jobTypes', function ($query) use ($jobTypes) {
        // Check if the first element of $jobTypes is numeric (ID) or a string
        if (is_numeric($jobTypes[0])) {
          // Use job type IDs
          $query->whereIn('job_types.id', $jobTypes); // Assuming 'id' is the primary key of job_types table
        } else {
          // Use job type strings
          $query->whereIn('job_type', $jobTypes);
        }
      });
    }

    // Filter job postings by the specified work location types
    if (!empty($workLocationTypes)) {
      $query->whereHas('workLocationTypes', function ($query) use ($workLocationTypes) {
        // Check if the first element of $workLocationTypes is numeric (ID) or a string
        if (is_numeric($workLocationTypes[0])) {
          // Use work location type IDs
          $query->whereIn('work_location_types.id', $workLocationTypes); // Assuming 'id' is the primary key of work_location_types table
        } else {
          // Use work location type strings
          $query->whereIn('name', $workLocationTypes);
        }
      });
    }

    // Filter job postings by the specified minimum salary
    if ($minSalary !== null) {
      $query->where('salary', '>=', $minSalary);
    }

    // Filter job postings by the specified maximum salary
    if ($maxSalary !== null) {
      $query->where('salary', '<=', $maxSalary);
    }

    // Filter job postings that require any of the skills provided
    if (!empty($skills)) {
      $query->whereHas('skills', function ($query) use ($skills) {
        $query->whereIn('name', $skills);
      });
    }

    // Filter job postings by location
    if ($location) {
      $query->where('location', 'like', "%$location%");
    }

    // Order job postings
    $query->orderBy($orderBy, $orderDirection);

    // Retrieve paginated job postings
    $jobPostings = $query->paginate($perPage);

    return JobPreliminaryResource::collection($jobPostings);
  }

  // |--------------------------------------------------------------------------
  public function store($validatedRequest): void {
    // Create the job
    $job = Job::create($validatedRequest);

    // Sync skills
    if (isset($validatedRequest['skills']) && is_array($validatedRequest['skills'])) {
      $skillIds = [];

      foreach ($validatedRequest['skills'] as $skill) {
        if (is_numeric($skill)) {
          $skillIds[] = $skill;
        } else {
          $existingSkill = Skill::where('name', $skill)->first();
          if ($existingSkill) {
            $skillIds[] = $existingSkill->id;
          }
        }
      }

      $job->skills()->sync($skillIds);
    }

    // Sync job types
    if (isset($validatedRequest['job_types']) && is_array($validatedRequest['job_types'])) {
      $job->jobTypes()->sync($validatedRequest['job_types']);
    }

    // Sync job work location types
    if (isset($validatedRequest['work_location_types']) && is_array($validatedRequest['work_location_types'])) {
      $job->workLocationTypes()->sync($validatedRequest['work_location_types']);
    }
  }


  // |--------------------------------------------------------------------------
  /**
   * Show job details using its ID.
   *
   * @param int $jobId The ID of the job.
   * @return \App\Http\Resources\JobDetailsResource A resource representing the job details.
   */
  public function showById($jobId) {
    $job = Job::with('company', 'skills', 'jobTypes', 'workLocationTypes')
      ->where('id', $jobId)
      ->firstOrFail();

    return new JobDetailsResource($job);
  }

  // |--------------------------------------------------------------------------
  /**
   * Show job details using its slug.
   *
   * @param string $jobSlug The slug of the job.
   * @return \App\Http\Resources\JobDetailsResource A resource representing the job details.
   */
  public function showBySlug($jobSlug) {
    $job = Job::with('company', 'skills', 'jobTypes', 'workLocationTypes')
      ->where('slug', $jobSlug)
      ->firstOrFail();

    return new JobDetailsResource($job);
  }

  // |--------------------------------------------------------------------------
  public function update($validatedRequest, $job): void {

    $job->fill($validatedRequest)->save();

    if (isset($validatedRequest['skills']) && is_array($validatedRequest['skills'])) {
      $skillIds = [];

      foreach ($validatedRequest['skills'] as $skill) {
        if (is_numeric($skill)) {
          $skillIds[] = $skill;
        } else {
          $existingSkill = Skill::where('name', $skill)->first();
          if ($existingSkill) {
            $skillIds[] = $existingSkill->id;
          }
        }
      }

      $job->skills()->sync($skillIds);
    }

    // Sync job types
    if (isset($validatedRequest['job_types']) && is_array($validatedRequest['job_types'])) {
      $job->jobTypes()->sync($validatedRequest['job_types']);
    }

    // Sync job work location types
    if (isset($validatedRequest['work_location_types']) && is_array($validatedRequest['work_location_types'])) {
      $job->workLocationTypes()->sync($validatedRequest['work_location_types']);
    }
  }


  // |--------------------------------------------------------------------------
  public function destroy($job) {
    $job->delete();
  }

  // implemented filter on index that can handle searching filter. update frontend and remove this
  // |--------------------------------------------------------------------------
  public function searchJobs($validatedRequest) {
    $keyword = $validatedRequest['keyword'];
    return JobPreliminaryResource::collection(Job::where('title', 'like', "%$keyword%")
      ->with('company', 'skills', 'jobTypes', 'workLocationTypes')
      ->orderBy('created_at', 'desc')
      ->paginate(10));
  }

  // |--------------------------------------------------------------------------
  public function searchJobSuggestions($request) {
    $keyword = $request['keyword'];

    if (empty($keyword)) {
      return response()->json([
        'suggestions' => [],
      ]);
    }

    $suggestions = Job::where('title', 'like', "%$keyword%")
      ->distinct()
      ->pluck('title')
      ->take(10);

    return $suggestions;
  }

  // |--------------------------------------------------------------------------
  /**
   * Add a single skill to the specified job.
   *
   * @param \App\Models\Skill $skill The skill to be added to the job.
   * @param \App\Models\Job $job The job to which the skill will be added.
   * @return void
   */
  public function jobSkillAdd($skill, $job) {
    $skillId = $skill->id;
    $job->skills()->attach($skillId);
  }

  // |--------------------------------------------------------------------------
  /**
   * Add multiple skills to the specified job based on the validated request.
   *
   * @param array $validatedRequest The validated request containing the skills to be added.
   * @param \App\Models\Job $job The job to which the skills will be added.
   * @return void
   */
  public function jobSkillsAdd(array $validatedRequest, $job) {
    $skills = $validatedRequest['skills'];

    // Ensure $skills is an array, even if it's a single ID
    if (!is_array($skills)) {
      $skills = [$skills];
    }

    // Add the skills to the job
    $job->skills()->syncWithoutDetaching($skills);
  }

  // |--------------------------------------------------------------------------
  /**
   * Remove a single skill from the specified job.
   *
   * @param mixed $skill The skill to be removed from the job.
   * @param mixed $job The job from which the skill will be removed.
   * @return void
   */
  public function jobSkillRemove($skill, $job) {
    $skillId = $skill->id;
    $job->skills()->detach($skillId);
  }

  // |--------------------------------------------------------------------------
  /**
   * Remove multiple skills from the specified job based on the validated request.
   *
   * @param mixed $skill The skill to be removed from the job.
   * @param mixed $job The job from which the skill will be removed.
   * @return void
   */
  public function jobSkillsRemove($validatedRequest, $job) {
    $skills = $validatedRequest['skills'];
    $job->skills()->detach($skills);
  }

  // |--------------------------------------------------------------------------
  public function updateJobTypes($validatedRequest, $job) {
    $jobTypes = $validatedRequest['job_types'];
    $job->jobTypes()->sync($jobTypes);
  }

  public function updateWorkLocationTypes($validatedRequest, $job) {
    $workLocationTypes = $validatedRequest['work_location_types'];
    $job->workLocationTypes()->sync($workLocationTypes);
  }
}
