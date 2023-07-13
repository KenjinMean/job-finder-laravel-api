<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyService {
    public function getCompanies(): AnonymousResourceCollection {
        // try {
        //     // Simulating a faulty database query, comment to return companies
        //     throw new QueryException(
        //         'HY000',
        //         'SELECT * FROM non_existing_table',
        //         [], // Bindings
        //         new \Exception('Simulated database query error, uncomment me in App\Services\CompanyService'),
        //     );
        // } catch (QueryException $e) {
        //     throw $e;
        // }
        return CompanyResource::collection(Company::query()->orderBy('id', 'desc')->paginate());
    }

    public function createCompany($validatedRequest): void {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            [
                'name' => $name,
                'website' => $website,
                'location' => $location,
                'description' => $description,
                'industry' => $industry,
                'company_logo' => $companyLogo
            ] = $validatedRequest;

            $companyData = [
                'user_id' => $user->id,
                'name' => $name,
                'website' => $website,
                'location' => $location,
                'description' => $description,
                'industry' => $industry,
            ];
            if (empty($companyLogo)) {
                $companyData['company_logo'] = "storage/company_logos/default-company-logo.png";
            } else {
                $path = Storage::disk('public')->put('company_logos', $companyLogo);
                $path = 'storage/' . str_replace('\\', '/', $path);
                $companyData['company_logo'] = $path;
            }
            Company::create($companyData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function showCompany($company): JsonResponse {
        return response()->json(new CompanyResource($company));
    }

    public function updateCompany($company, $validatedRequest): void {
        $company->update([
            'name' => $validatedRequest['name'],
            'website' => $validatedRequest['website'],
            'location' => $validatedRequest['location'],
            'description' => $validatedRequest['description'],
            'industry' => $validatedRequest['industry'],
        ]);
    }

    public function deleteCompany($company) {
        $company->delete();
    }

    public function showUserCompanies($user): JsonResponse {
        return response()->json($user->companies);
    }

    public function updateCompanyImage($company, $validatedRequest): void {
        try {
            $path = null;
            $oldCompanyImage = $company->company_logo;
            DB::beginTransaction();

            if (!empty($validatedRequest['company_logo'])) {
                // New image is provided
                $path = Storage::disk('public')->put('company_logos', $validatedRequest['company_logo']);
                $path = 'storage/' . str_replace('\\', '/', $path);
                $company->update([
                    "company_logo" => $path
                ]);
            } elseif ($oldCompanyImage !== 'storage/company_logos/default-company-logo.png') {
                // No image is provided, and the old image is not the default image
                $oldCompanyImage = str_replace('storage/', '', $oldCompanyImage);
                $path = Storage::disk('public')->delete($oldCompanyImage);
                $company->update([
                    "company_logo" => "storage/company_logos/default-company-logo.png"
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            try {
                $path = str_replace('storage/', '', $oldCompanyImage);
                Storage::disk('public')->delete($path);
            } catch (\Throwable $deleteException) {
                throw $deleteException;
            }
            throw $e;
        }
    }
}
