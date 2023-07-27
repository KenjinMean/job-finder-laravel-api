<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Company;
use App\Helpers\ExceptionHelper;
use App\Helpers\JwtHelper;
use App\Services\CompanyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\CompanyUpdateImageRequest;

class CompanyController extends Controller {

    private $companyService;

    public function __construct(CompanyService $companyService) {
        $this->companyService = $companyService;
    }

    public function index() {
        try {
            return $this->companyService->getCompanies();
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function store(StoreCompanyRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->companyService->createCompany($user, $validatedRequest);
            return response()->json(['message' => 'Company created successfully']);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function show(Company $company) {
        try {
            return  $this->companyService->showCompany($company);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function update(UpdateCompanyRequest $request, Company $company) {
        try {
            $validatedRequest = $request->validated();
            $this->authorize('update', $company);
            $this->companyService->updateCompany($company, $validatedRequest);
            return response()->json(['message' => 'Company updated successfully']);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function destroy(Company $company) {
        try {
            $this->authorize('delete', $company);
            $this->companyService->deleteCompany($company);
            return response()->json(['message' => 'company deleted successfully']);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function showUserCompanies() {
        try {
            $user = JwtHelper::getUserFromToken();
            return $this->companyService->showUserCompanies($user);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function updateCompanyImage(CompanyUpdateImageRequest $request, Company $company) {
        try {
            $validatedRequest = $request->validated();
            $this->authorize('updateCompanyImage', $company);
            $this->companyService->updateCompanyImage($company, $validatedRequest);
            return response()->json(["message" => "company image update successfully"]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
