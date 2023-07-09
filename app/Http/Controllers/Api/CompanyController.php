<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Company;
use App\Services\CompanyService;
use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
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
            $this->companyService->createCompany($request->validated());
            return response()->json(['message' => 'Company created successfully']);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function show(int $id) {
        try {
            return  $this->companyService->showCompany($id);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function update(UpdateCompanyRequest $request, $companyId) {
        try {
            $company = Company::findOrFail($companyId);
            $validatedRequest = $request->validated();
            $this->authorize('update', $company);
            $this->companyService->updateCompany($company, $validatedRequest);
            return response()->json(['message' => 'Company updated successfully']);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function destroy(int $companyId) {
        try {
            $company = Company::findOrFail($companyId);
            $this->authorize('delete', $company);
            $this->companyService->deleteCompany($company);
            return response()->json(['message' => 'company deleted successfully']);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function showUserCompanies() {
        try {
            $user = Auth::user();
            return $this->companyService->showUserCompanies($user);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }

    public function updateCompanyImage(CompanyUpdateImageRequest $request, $companyId) {
        try {
            $company = Company::findOrFail($companyId);
            $validatedRequest = $request->validated();
            $this->companyService->updateCompanyImage($company, $validatedRequest);
            return response()->json(["message" => "company image update successfully"]);
        } catch (Throwable $e) {
            return ExceptionHelper::handleException($e);
        }
    }
}
