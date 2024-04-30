<?php

namespace App\Services;

use App\Models\Company;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;

class UserCompanyService {
  public function index($user) {
    return CompanyResource::collection($user->companies);
  }

  // |--------------------------------------------------------------------------
  public function show($company): CompanyResource {
    return new CompanyResource($company);
  }

  // |--------------------------------------------------------------------------
  public function store($user, $validatedRequest): void {
    $validatedRequest['user_id'] = $user->id;
    Company::create($validatedRequest);
  }

  // |--------------------------------------------------------------------------
  public function update($company, $validatedRequest): void {
    $company->fill($validatedRequest)->save();
  }

  // |--------------------------------------------------------------------------
  public function delete($company): void {
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
