<?php

namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
use App\Http\Requests\{AttachJobApplierRequest, DetachJobApplierRequest, UpdateApplierRequest};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApplierController extends Controller
{
	public function update(UpdateApplierRequest $request)
	{
		$authenticated_user = auth()->user();
		$authenticated_user->fill($request->validated());
		$authenticated_user->update();
		return true;
	}

	public function attachJob(AttachJobApplierRequest $request, Job $job)
	{
		$authenticated_user = auth()->user();

		abort_if($authenticated_user->hasAppliedFor($job), 400, __('You have already applied for that job.'));
		
		$authenticated_user->jobs()->attach($job, [
			'message' => $request->input('message')
		]);

		$job_application = JobUser::where([
			[
				'job_id', '=', $job->getKey()
			],
			[
				'user_id', '=', $authenticated_user->getKey()
			]
		])->firstOrFail();
		$job->firm->notify(new NewJobApplication($job_application));
	}

	public function detachJob(DetachJobApplierRequest $request, Job $job)
	{
		auth()->user()->jobs()->detach($job);
	}
}
