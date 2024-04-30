<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\Skill;
use App\Services\JobService;
use Illuminate\Http\Response;
use App\Http\Requests\JobRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchJobRequest;
use App\Http\Requests\UpdateJobTypeRequest;
use App\Http\Requests\UpdateJobSkillRequest;
use App\Http\Requests\FetchJobPostingsRequest;
use App\Http\Requests\UpdateworkLocationTypeRequest;

class JobController extends Controller {

    private $jobService;

    public function __construct(JobService $jobService) {
        $this->jobService = $jobService;
    }

    // |--------------------------------------------------------------------------
    public function index(FetchJobPostingsRequest $request) {
        $validatedRequest = $request->validated();

        return $this->jobService->index($validatedRequest);
    }

    // FIX: fix policies to match for updating jobs
    // |--------------------------------------------------------------------------
    public function store(JobRequest $request) {
        $validatedRequest = $request->validated();

        $job = new Job;
        $companyId = $validatedRequest['company_id'];

        // fix auth use gate instead
        // Passes the Job instance and the associated company ID as parameters
        // to let Laravel know what policy to run.
        $this->authorize('store', [$job,  $companyId]);

        $this->jobService->store($validatedRequest);

        return response()->json(['message' => 'Company created successfully'], Response::HTTP_CREATED);
    }

    // |--------------------------------------------------------------------------
    /**
     * Fetch job details using its ID.
     *
     * @param int $jobId The ID of the job.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the job details.
     */
    public function showById(int $jobId): JsonResponse {
        $job = $this->jobService->showById($jobId);

        return response()->json(["job" => $job], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    /**
     * Fetch job details using its slug.
     *
     * @param string $jobSlug The slug of the job.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the job details.
     */
    public function showBySlug($jobSlug): JsonResponse {
        $job = $this->jobService->showBySlug($jobSlug);

        return response()->json(["job" => $job], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function update(JobRequest $request, Job $job) {
        $this->authorize("update", $job);

        $validatedRequest = $request->validated();
        $this->jobService->update($validatedRequest, $job);

        return response()->json(["message" => "Job updated successfully"], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function destroy(Job $job) {
        $this->authorize('delete', $job);

        $this->jobService->destroy($job);

        return response()->json(["message" => "Job deleted successfully"], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function searchJobs(SearchJobRequest $request) {
        // implemented filter on index that can handle searching filter. can update frontend and remove this
        $validatedRequest = $request->validated();
        return $this->jobService->searchJobs($validatedRequest);
    }

    // |--------------------------------------------------------------------------
    public function searchJobSuggestions(SearchJobRequest $request) {
        $validatedRequest = $request->validated();
        $suggestions = $this->jobService->searchJobSuggestions($validatedRequest);

        return response()->json(['suggestions' => $suggestions], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    /**
     * Add a single skill to the specified job.
     *
     * @param \App\Models\Job $job The job to which the skill will be added.
     * @param \App\Models\Skill $skill The skill to be added to the job.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation.
     */
    public function jobSkillAdd(Job $job, Skill $skill) {
        $this->authorize('update', $job);

        $this->jobService->jobSkillAdd($skill, $job);

        return response()->json(["message" => "Job skills added successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    /**
     * Add multiple skills to the specified job based on the validated request.
     *
     * @param \App\Http\Requests\UpdateJobSkillRequest $request The validated request containing the skills to be added.
     * @param \App\Models\Job $job The job to which the skills will be added.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation.
     */
    public function jobSkillsAdd(UpdateJobSkillRequest $request, Job $job) {
        $this->authorize('update', $job);

        $validatedRequest = $request->validated();
        $this->jobService->jobSkillsAdd($validatedRequest, $job);

        return response()->json(["message" => "Job skills added successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    /**
     * Remove a single skill from the specified job.
     *
     * @param \App\Models\Job $job The job from which the skill will be removed.
     * @param \App\Models\Skill $skill The skill to be removed from the job.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation.
     */
    public function jobSkillRemove(Job $job, Skill $skill) {
        $this->authorize('update', $job);

        $this->jobService->jobSkillRemove($skill, $job);

        return response()->json(["message" => "Job skill removed successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    /**
     * Remove multiple skills from the specified job based on the validated request.
     *
     * @param \App\Http\Requests\UpdateJobSkillRequest $request The validated request containing the skills to be removed.
     * @param \App\Models\Job $job The job from which the skills will be removed.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation.
     */
    public function jobSkillsRemove(UpdateJobSkillRequest $request, Job $job) {
        $this->authorize('update', $job);

        $validatedRequest = $request->validated();
        $this->jobService->jobSkillsRemove($validatedRequest, $job);

        return response()->json(["message" => "Job skills removed successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateJobTypes(UpdateJobTypeRequest $request, Job $job) {
        $this->authorize('update', $job);

        $validatedRequest = $request->validated();
        $this->jobService->updateJobTypes($validatedRequest, $job);

        return response()->json(["message" => "Job type updated successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateWorkLocationTypes(UpdateworkLocationTypeRequest $request, Job $job) {
        $this->authorize('update', $job);

        $validatedRequest = $request->validated();
        $this->jobService->updateWorkLocationTypes($validatedRequest, $job);

        return response()->json(["message" => "Work Location type updated successfully."], Response::HTTP_OK);
    }
}
