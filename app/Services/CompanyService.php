<?php

namespace App\Services;

use App\Models\Company;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyService {
    public function getCompanies(): AnonymousResourceCollection {
        return CompanyResource::collection(Company::query()->orderBy('id', 'desc')->paginate(10));
    }

    // |--------------------------------------------------------------------------
    public function showCompany($company): CompanyResource {
        return new CompanyResource($company);
    }

    // |--------------------------------------------------------------------------
    public function createCompany($user, $validatedData): CompanyResource {
        $validatedData['user_id'] = $user->id;
        $company = Company::create($validatedData);
        return new CompanyResource($company);
    }

    // |--------------------------------------------------------------------------
    public function updateCompany($company, $validatedRequest): CompanyResource {
        $company->fill($validatedRequest)->save();
        return new CompanyResource($company);
    }

    // |--------------------------------------------------------------------------
    public function updateCompanyLogo($validatedRequest, $company): void {

        if (empty($validatedRequest['company_logo'])) {
            return;
        }

        $oldCompanyLogo = $company->company_logo;

        if ($oldCompanyLogo && Storage::disk('public')->exists($oldCompanyLogo)) {
            Storage::disk('public')->delete($oldCompanyLogo);
        }

        $companyImagePath = Storage::disk('public')->put('company_logos', $validatedRequest['company_logo']);
        // Convert backslashes to forward slashes in file path
        $companyImagePath = 'storage/' . str_replace('\\', '/', $companyImagePath);
        $company->company_logo = $companyImagePath;

        $company->save();
    }

    // |--------------------------------------------------------------------------
    public function deleteCompanyLogo($company): void {
        $companyLogo = $company->company_logo;

        if ($companyLogo !== null) {
            if (Storage::disk('public')->exists($companyLogo)) {
                Storage::disk('public')->delete($companyLogo);
            }
            $company->company_logo = null;
            $company->save();
        }
    }

    // |--------------------------------------------------------------------------
    public function deleteCompany($company): void {
        $company->delete();
    }
}
