<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Job;
use App\Services\JobService;
use App\Helpers\ExceptionHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchJobRequest;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Requests\UpdateJobSkillRequest;

class JobController extends Controller {

    private $jobService;

    public function __construct(JobService $jobService) {
        $this->jobService = $jobService;
    }

    public function index() {
        try {
            return $this->jobService->index();
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function store(StoreJobRequest $request) {
        try {
            $validatedRequest = $request->validated();
            $this->jobService->createJob($validatedRequest);
            return response()->json(['message' => 'Company created successfully']);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function show(int $jobId): JsonResponse {
        try {
            $job = $this->jobService->showJob($jobId);
            return response()->json(["job" => $job]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function update(UpdateJobRequest $request, int $jobId) {
        try {
            $job = Job::findOrFail($jobId);
            $this->authorize("update", $job);
            $validatedRequest = $request->validated();
            $this->jobService->updateJob($validatedRequest, $job);
            return response()->json(["message" => "Updated job successfully"]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function destroy(int $jobId) {
        try {
            $job = Job::findOrFail($jobId);
            $this->authorize('delete', $job);
            $this->jobService->deleteJob($job);
            return response()->json(["message" => "Job deleted successfully"]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function getJobPostings() {
        try {
            return $this->jobService->getPaginatedJobsWithDetails();
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function searchJobs(SearchJobRequest $request) {
        try {
            $validatedData = $request->validated();
            $keyword = $validatedData['keyword'];
            return $this->jobService->searchJobs($keyword);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function updateJobSkills(UpdateJobSkillRequest $request, int $jobId) {
        try {
            $job = Job::findOrFail($jobId);
            $this->authorize('updateJobSkill', $job);
            $skills = $request->validated()['skills'];
            $this->jobService->updateJobSkills($skills, $job);
            return response()->json([
                "message" => "Job skills updated successfully."
            ]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
