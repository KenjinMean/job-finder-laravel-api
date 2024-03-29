<?php

namespace App\Providers;

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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        $this->registerPolicies();
    }
}
