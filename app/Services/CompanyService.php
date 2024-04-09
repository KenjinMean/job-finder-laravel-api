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
    public function deleteCompany($company): void {
        $company->delete();
    }

    // |--------------------------------------------------------------------------
    public function updateCompanyLogo($validatedRequest, $company): void {
        $newCompanyLogoPath = Storage::disk('public')->put('company_logos', $validatedRequest['company_logo']);

        $existingCompanyLogo = $company->company_logo;


        if ($existingCompanyLogo && Storage::disk('public')->exists(str_replace('storage/', '', $existingCompanyLogo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $existingCompanyLogo));
        }

        // Convert backslashes to forward slashes in file path
        $newCompanyLogoPath = 'storage/' . str_replace('\\', '/', $newCompanyLogoPath);
        $company->company_logo = $newCompanyLogoPath;

        $company->save();
    }

    // |--------------------------------------------------------------------------
    public function deleteCompanyLogo($company): void {
        $existingCompanyLogo = $company->company_logo;

        if ($existingCompanyLogo && Storage::disk('public')->exists(str_replace('storage/', '', $existingCompanyLogo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $existingCompanyLogo));

            $company->company_logo = null;
            $company->save();
        }
    }
}
