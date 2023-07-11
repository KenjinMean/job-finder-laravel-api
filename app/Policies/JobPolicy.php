<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy {

    public function update(User $user, Job $job): bool {
        $companies = $user->companies()->pluck('id');
        return $companies->contains($job->company_id);
    }

    public function delete(User $user, Job $job): bool {
        $companies = $user->companies()->pluck('id');
        return $companies->contains($job->company_id);
    }

    public function updateJobSkill(User $user, Job $job): bool {
        $company = $job->company;
        return $user->companies->contains($company);
    }
}
