<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Skill;
use App\Http\Resources\users\UserCompanyJobResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserCompanyJobService {
  public function index($company) {
    return $company->jobs()->get();
  }

  // |--------------------------------------------------------------------------
  public function show($company, $job) {
    if ($job->company_id !== $company->id) {
      throw new NotFoundHttpException('Job not found for the specified company');
    }
    $job->load('jobTypes', 'skills', 'workLocationTypes');

    return new UserCompanyJobResource($job);
  }

  // |--------------------------------------------------------------------------
  public function store($validatedRequest) {
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
  public function update($validatedRequest, $job) {
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
  public function delete($job) {
    $job->delete();
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
