<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy {

    public function update(User $user, Company $company): bool {
        return $user->companies->contains($company);
    }

    public function updateCompanyImage(User $user, Company $company): bool {
        return $user->companies->contains($company);
    }

    public function delete(User $user, Company $company): bool {
        return $user->companies->contains($company);
    }
}
