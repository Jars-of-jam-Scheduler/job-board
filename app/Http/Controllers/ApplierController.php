<?php

namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
use App\Http\Requests\{AcceptOrRefuseJobApplicationUserRequest, UpdateUserRequest};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApplierController extends UserController
{
	public function update(UpdateUserRequest $request)
	{
		parent::update($request);

		if($request->has('job')) {
			$this->attachOrDetachJob($request);
		}
	
		return true;
	}

	private function attachOrDetachJob(Request $request)
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
}
