<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CompanyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyUpdateImageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller {

    private $companyService;

    public function __construct(CompanyService $companyService) {
        $this->companyService = $companyService;
    }

    public function index(): AnonymousResourceCollection {
        try {
            return $this->companyService->getCompanies();
        } catch (QueryException $e) {
            return response()->json(['message' => 'error creating job', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreCompanyRequest $request) {
        try {
            $this->companyService->createCompany($request->validated());
            return response()->json(['message' => 'Company created successfully']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            $errorMessage = $e instanceof QueryException ? 'Error creating company' : $e->getMessage();
            $statusCode = $e instanceof QueryException ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_BAD_REQUEST;
            return response()->json(['message' => $errorMessage, 'error' => $e->getMessage()], $statusCode);
        }
    }

    public function show(string $id) {
        try {
            return  $this->companyService->showCompany($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving user company', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCompanyRequest $request, $companyId) {
        $company = Company::findOrFail($companyId);
        $validatedRequest = $request->validated();
        $this->authorize('update', $company);
        try {
            $this->companyService->updateCompany($company, $validatedRequest);
            return response()->json(['message' => 'Company updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'error updating company', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(string $companyId) {
        $company = Company::findOrFail($companyId);
        $this->authorize('delete', $company);
        try {
            $this->companyService->deleteCompany($company);
            return response()->json(['message' => 'company deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error deleting company', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showUserCompanies() {
        $user = Auth::user();
        try {
            return $this->companyService->showUserCompanies($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error retrieving user companies', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCompanyImage(CompanyUpdateImageRequest $request, $companyId) {
        $company = Company::findOrFail($companyId);
        $validatedRequest = $request->validated();
        try {
            $this->companyService->updateCompanyImage($company, $validatedRequest);
            return response()->json(["message" => "company image update successfully"]);
        } catch (QueryException $qe) {
            return response()->json(["message" => "Error Updating Company", "error" => $qe->getMessage()]);
        } catch (FilesystemException $fe) {
            return response()->json(["message" => "Error Storing Image", "error" => $fe->getMessage()]);
        } catch (\Exception $e) {
            return response()->json(["message" => "An unexpected error occurred", "error" => $e->getMessage()]);
        }
    }
}
