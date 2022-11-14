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
			return $user->hasRole('firm');
		});

		Gate::define('update-job', function(User $user, Job $job) {
			return $user->hasRole('firm') && $job->firm_id == $user->getKey();
		});

		Gate::define('destroy-job', function(User $user, Job $job) {
			return $user->hasRole('firm') && $job->firm_id == $user->getKey();
		});

		Gate::define('attach-job-skill', function(User $user, Job $job) {
			return $user->hasRole('firm') && $job->firm_id == $user->getKey();
		});

		Gate::define('detach-job-skill', function(User $user, Job $job) {
			return $user->hasRole('firm') && $job->firm_id == $user->getKey();
		});

		Gate::define('accept-job-application', function(User $user, JobUser $job_application) {
			return $user->hasRole('firm') && $job_application->job->firm_id == $user->getKey();
		});

		Gate::define('refuse-job-application', function(User $user, JobUser $job_application) {
			return $user->hasRole('firm') && $job_application->job->firm_id == $user->getKey();
		});
	}

	private function defineApplierGates() : void
	{
		Gate::define('attach-job', function(User $user) {
			return $user->hasRole('job_applier');
		});

		Gate::define('detach-job', function(User $user, Job $job) {
			return $user->hasRole('job_applier') && $job->users()->where('user_id', $user->getKey())->exists();
		});
	}
}
