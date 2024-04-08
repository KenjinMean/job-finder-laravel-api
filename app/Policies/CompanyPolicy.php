<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy {

    public function index(): bool {
        return false;
    }

    public function update(User $user, Company $company): bool {
        return $user->id === $company->user_id;
    }

    public function delete(User $user, Company $company): bool {
        return $user->id === $company->user_id;
    }
}
