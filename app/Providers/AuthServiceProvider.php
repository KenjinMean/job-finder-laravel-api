<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Job;
use App\Models\Skill;
use App\Models\UserWorkExperience;
use App\Policies\SkillPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\JobSkillPolicy;
use App\Policies\UserWorkExperiencePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Skill::class => SkillPolicy::class,
        UserWorkExperience::class => UserWorkExperiencePolicy::class,
        Company::class => CompanyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        $this->registerPolicies();

        Gate::define('manage-user-company', function ($user, $company) {
            return $user->id === $company->user_id;
        });

        Gate::define('manage-user-company-job', function ($user, $company, $job) {
            return $user->id === $company->user_id &&  $company->id === $job->company_id;
        });
    }
}
