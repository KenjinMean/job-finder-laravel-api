<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy {

    public function index(User $user, Job $job): bool {
        return false;
    }

    public  function store(User $user, Job $job, $companyId) {
        return $user->companies()->where('id', $companyId)->exists();
    }

    public function update(User $user, Job $job): bool {
        $company = $job->company;
        return $user->companies->contains($company);
    }

    public function delete(User $user, Job $job): bool {
        $company = $job->company;
        return $user->companies->contains($company);
    }
}
