<?php

namespace App\Http\Controllers\api;

use App\Models\Company;
use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\UserCompanyService;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\CompanyUpdateImageRequest;

class UserCompanyController extends Controller {

    private $userCompanyService;

    public function __construct(UserCompanyService $userCompanyService) {
        $this->userCompanyService = $userCompanyService;
    }

    // |--------------------------------------------------------------------------
    public function index() {
        $user = JwtHelper::getUserFromToken();
        $userCompanies = $this->userCompanyService->index($user);

        return response()->json(["user_companies" => $userCompanies], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function show(Company $company) {
        $this->authorize('manage-user-company', $company);

        return $this->userCompanyService->show($company);
    }

    // |--------------------------------------------------------------------------
    public function store(StoreCompanyRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $this->userCompanyService->store($user, $validatedRequest);

        return response()->json(['message' => 'Company Created successfully.'], Response::HTTP_CREATED);
    }

    // |--------------------------------------------------------------------------
    public function update(UpdateCompanyRequest $request, Company $company) {
        $this->authorize('manage-user-company', $company);
        $validatedRequest = $request->validated();
        $this->userCompanyService->update($company, $validatedRequest);

        return response()->json(['message' => 'Company Updated successfully.'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function delete(Company $company) {
        $this->authorize('manage-user-company', $company);
        $this->userCompanyService->delete($company);

        return response()->json(['message' => 'Company Deleted successfully.'], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateCompanyLogo(CompanyUpdateImageRequest $request, Company $company) {
        $this->authorize('manage-user-company', $company);
        $validatedRequest = $request->validated();
        $this->userCompanyService->updateCompanyLogo($validatedRequest, $company);

        return response()->json(["message" => "company image updated successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function deleteCompanyLogo(Company $company) {
        $this->authorize('manage-user-company', $company);
        $this->userCompanyService->deleteCompanyLogo($company);

        return response()->json(["message" => "company logo deleted successfully."], Response::HTTP_OK);
    }
}
