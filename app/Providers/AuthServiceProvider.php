<?php

namespace App\Providers;

use App\Models\{User, Job, JobUser};

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

		$this->defineFirmGates();
		$this->defineApplierGates();
    }

	private function defineFirmGates() : void
	{
		Gate::define('store-job', function(User $user) {
			return $user->hasRole('firm') && !$user->hasRole('job_applier');
		});

		Gate::define('destroy-job', function(User $user, Job $job) {
			return $user->hasRole('firm') && !$user->hasRole('job_applier') && $job->firm_id == $user->getKey();
		});

		Gate::define('update-job-firm', function(User $user, Job $job) {
			return $user->hasRole('firm') && !$user->hasRole('job_applier') && $job->firm_id == $user->getKey();
		});

		Gate::define('restore-job-firm', function(User $user, Job $job) {
			return $user->hasRole('firm') && !$user->hasRole('job_applier') && $job->firm_id == $user->getKey();
		});

		Gate::define('accept-or-refuse-job-application', function(User $user, JobUser $job_application) {
			return $user->hasRole('firm') && !$user->hasRole('job_applier') && $job_application->job->firm_id == $user->getKey();
		});
	}

	private function defineApplierGates() : void
	{
		Gate::define('attach-job', function(User $user) {
			return $user->hasRole('job_applier') && !$user->hasRole('firm');
		});

		Gate::define('detach-job', function(User $user, Job $job) {
			return $user->hasRole('job_applier') && !$user->hasRole('firm') && $job->users()->where('user_id', $user->getKey())->exists();
		});
	}
}
