<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Services\CompanyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\CompanyUpdateImageRequest;

class CompanyController extends Controller {

    private $companyService;

    public function __construct(CompanyService $companyService) {
        $this->companyService = $companyService;
    }

    // |--------------------------------------------------------------------------
    public function index() {
        $this->authorize('index');
        $companies = $this->companyService->getCompanies();
        return response()->json(["companies" =>   $companies], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function store(StoreCompanyRequest $request) {
        $user = JwtHelper::getUserFromToken();
        $validatedRequest = $request->validated();
        $company = $this->companyService->createCompany($user, $validatedRequest);
        return response()->json(['message' => 'Company created successfully.', "company" => $company], Response::HTTP_CREATED);
    }

    // |--------------------------------------------------------------------------
    public function show(Company $company) {
        $companyData =  $this->companyService->showCompany($company);
        return response()->json(["company" => $companyData], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function update(UpdateCompanyRequest $request, Company $company) {
        $this->authorize('update', $company);
        $validatedRequest = $request->validated();
        $updatedCompany = $this->companyService->updateCompany($company, $validatedRequest);
        return response()->json(['message' => 'Company updated successfully.', "company" => $updatedCompany], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function updateCompanyImage(CompanyUpdateImageRequest $request, Company $company) {
        $this->authorize('update', $company);
        $validatedRequest = $request->validated();
        $this->companyService->updateCompanyLogo($validatedRequest, $company);
        return response()->json(["message" => "company image updated successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function deleteCompanyLogo(Company $company) {
        $this->authorize('update', $company);
        $this->companyService->deleteCompanyLogo($company);
        return response()->json(["message" => "company logo deleted successfully."], Response::HTTP_OK);
    }

    // |--------------------------------------------------------------------------
    public function destroy(Company $company) {
        $this->authorize('delete', $company);
        $this->companyService->deleteCompany($company);
        return response()->json(["message" => "company deleted successfully."], Response::HTTP_OK);
    }
}
