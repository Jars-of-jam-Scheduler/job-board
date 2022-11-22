<?php

namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
use App\Http\Requests\UpdateUserRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
	public function update(UpdateUserRequest $request, User $user)
	{
		Gate::authorize('update-user', $user);

		if($request->has('job')) {
			$this->attachOrDetachJob($request);
		}

		$user->fill($request->validated());
		$user->update();
		return true;
	}
    
	public function attachOrDetachJob(Request $request)
	{
		$authenticated_user = auth()->user();
		if($request->input('job.attach_or_detach')) {
			Gate::authorize('attach-job');

			abort_if($authenticated_user->hasAppliedFor($request->input('job.id')), 400, __('You have already applied for that job.'));
			$authenticated_user->jobs()->attach($request->input('job.id'), [
				'message' => $request->input('job.message')
			]);

			$job_application = JobUser::where([
				[
					'job_id', '=', $request->input('job.id')
				],
				[
					'user_id', '=', $authenticated_user->getKey()
				]
			])->firstOrFail();
			Job::findOrFail($request->input('job.id'))->firm->notify(new NewJobApplication($job_application));
		} else {
			$job = Job::findOrFail($request->input('job.id'));
			Gate::authorize('detach-job', $job);

			$authenticated_user->jobs()->detach($request->input('job.id'));
		}
	}

	public function acceptOrRefuseJobApplication(Request $request) : AcceptedRefusedJobsApplicationsHistory
	{
		$job_application = JobUser::findOrFail($request->input('job_application.job_application_id'));

		Gate::authorize('accept-or-refuse-job-application', $job_application);

		$new_job_application_accept_or_refuse = AcceptedRefusedJobsApplicationsHistory::create([
			'accepted_or_refused' => $request->input('job_application.accept_or_refuse'), 
			'firm_message' => $request->input('job_application.firm_message'),
			'job_application_id' => $job_application->input('job_application.job_application_id')
		]);

		if($request->input('job_application.accept_or_refuse')) {
			$job_application->user->notify(new AcceptedJobApplication($job_application));

		} else {
			$job_application->user->notify(new RefusedJobApplication($job_application));

		}

		return $new_job_application_accept_or_refuse;
	}
}
