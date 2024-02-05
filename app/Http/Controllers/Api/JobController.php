<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\Request;
use App\Helpers\ExceptionHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterJobsRequest;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\SearchJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Requests\UpdateJobTypeRequest;
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

    /* Fetch job details using id */
    // public function show(int $jobId): JsonResponse {
    //     try {
    //         $job = $this->jobService->showJob($jobId);
    //         return response()->json(["job" => $job]);
    //     } catch (Throwable $e) {
    //         return ExceptionHelper::handleException($e);
    //     }
    // }

    /* FETCH job details using slug */
    public function show($jobSlug): JsonResponse {
        $job = $this->jobService->showJob($jobSlug);
        return response()->json(["job" => $job]);
    }

    public function update(UpdateJobRequest $request, Job $job) {
        $this->authorize("update", $job);
        $validatedRequest = $request->validated();
        $this->jobService->updateJob($validatedRequest, $job);
        return response()->json(["message" => "Updated job successfully"]);
    }

    public function destroy(Job $job) {
        try {
            $this->authorize('delete', $job);
            $this->jobService->deleteJob($job);
            return response()->json(["message" => "Job deleted successfully"]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    // PAGINATED JOBS by TEN
    public function getJobPostings() {
        try {
            return $this->jobService->getPaginatedJobsWithDetails();
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    // FETCH JOBS by TEN
    // public function getJobPostings(Request $request) {
    //     try {
    //         $page = $request->input('page', 1);
    //         return $this->jobService->getJobPostings($page);
    //     } catch (Throwable $e) {
    //         return ExceptionHelper::handleException($e);
    //     }
    // }

    public function searchJobs(SearchJobRequest $request) {
        try {
            $validatedData = $request->validated();
            $keyword = $validatedData['keyword'];
            return $this->jobService->searchJobs($keyword);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function searchJobSuggestions(Request $request) {
        try {
            $keyword = $request->query('keyword');
            return $this->jobService->searchJobsSuggestions($keyword);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function filterJobs(FilterJobsRequest $request) {
        try {
            return $this->jobService->filterJobs($request);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function updateJobSkills(UpdateJobSkillRequest $request, Job $job) {
        try {
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

    public function updateJobType(UpdateJobTypeRequest $request, Job $job) {
        try {
            $jobTypes = $request->validated()['job_type'];
            $this->jobService->updateJobType($jobTypes, $job);
            return response()->json([
                "message" => "Job type updated successfully."
            ]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
