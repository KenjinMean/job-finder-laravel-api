<?php

namespace App\Http\Controllers\api;

use App\Models\Job;
use App\Models\Skill;
use App\Models\Company;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\UserCompanyJobService;
use App\Http\Requests\UpdateJobTypeRequest;
use App\Http\Requests\UpdateJobSkillRequest;
use App\Http\Requests\UpdateworkLocationTypeRequest;
use App\Http\Requests\users\UserCompanyJobStoreRequest;
use App\Http\Requests\users\UserCompanyJobUpdateRequest;

class UserCompanyJobController extends Controller {

    private $userCompanyJobService;

    public function __construct(UserCompanyJobService $userCompanyJobService) {
        $this->userCompanyJobService = $userCompanyJobService;
    }

    // |--------------------------------------------------------------------------
    public function index(Company $company) {
        $this->authorize('manage-user-company', $company);

        $jobs =  $this->userCompanyJobService->index($company);

        return response()->json(["company_jobs" => $jobs], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function show(Company $company, Job $job) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $companyJob = $this->userCompanyJobService->show($company, $job);

        return response()->json(["company_job" => $companyJob], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function store(UserCompanyJobStoreRequest $request, Company $company) {
        $this->authorize('manage-user-company', $company);

        $validatedRequest = $request->validated();
        $validatedRequest['company_id'] = $company->id;
        $this->userCompanyJobService->store($validatedRequest);

        return response()->json(['message' => 'Job Created successfully.'], Response::HTTP_CREATED);
    }

    // |--------------------------------------------------------------------------
    public function update(UserCompanyJobUpdateRequest $request, Company $company, Job $job) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $validatedRequest = $request->validated();
        $this->userCompanyJobService->update($validatedRequest, $job);

        return response()->json(['message' => 'Job Updated successfully.'], Response::HTTP_CREATED);
    }


    // |--------------------------------------------------------------------------
    public function delete(Company $company, Job $job) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $this->userCompanyJobService->delete($job);

        return response()->json(['message' => 'Job Deleted successfully.'], Response::HTTP_CREATED);
    }

    // |--------------------------------------------------------------------------
    /**
     * Add a single skill to the specified job.
     *
     * @param \App\Models\Job $job The job to which the skill will be added.
     * @param \App\Models\Skill $skill The skill to be added to the job.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation.
     */
    public function jobSkillAdd(Company $company, Job $job, Skill $skill) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $this->userCompanyJobService->jobSkillAdd($skill, $job);

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
    public function jobSkillsAdd(UpdateJobSkillRequest $request, Company $company, Job $job) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $validatedRequest = $request->validated();
        $this->userCompanyJobService->jobSkillsAdd($validatedRequest, $job);

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
    public function jobSkillRemove(Company $company, Job $job, Skill $skill) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $this->userCompanyJobService->jobSkillRemove($skill, $job);

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
    public function jobSkillsRemove(UpdateJobSkillRequest $request, Company $company, Job $job) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $validatedRequest = $request->validated();
        $this->userCompanyJobService->jobSkillsRemove($validatedRequest, $job);

        return response()->json(["message" => "Job skills removed successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateJobTypes(UpdateJobTypeRequest $request, Company $company, Job $job) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $validatedRequest = $request->validated();
        $this->userCompanyJobService->updateJobTypes($validatedRequest, $job);

        return response()->json(["message" => "Job type updated successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateWorkLocationTypes(UpdateworkLocationTypeRequest $request, Company $company, Job $job) {
        $this->authorize('manage-user-company-job', [$company, $job]);

        $validatedRequest = $request->validated();
        $this->userCompanyJobService->updateWorkLocationTypes($validatedRequest, $job);

        return response()->json(["message" => "Work Location type updated successfully."], Response::HTTP_OK);
    }
}
