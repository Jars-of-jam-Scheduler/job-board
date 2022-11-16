<?php

namespace App\Http\Controllers;

<<<<<<< Updated upstream
use App\Models\{Job, User};
=======
use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
>>>>>>> Stashed changes

use Illuminate\Http\Request;

class UserController extends Controller
{
    
	public function attachJob(Request $request) : JobUser
	{
		$validated = $request->validate([
			'user' => 'required|integer|gt:0',
			'job' => 'required|integer|gt:0',
			'message' => 'nullable|string'
		]);

		$user = User::findOrFail($validated['user']);

		abort_if($user->hasAppliedFor($validated['job']), 400, __('You have already applied for that job.'));

		$user->jobs()->attach($validated['job'], [
			'message' => $validated['message']
		]);

		$job_application = JobUser::where([
			[
				'job_id', '=', $request->job
			],
			[
				'user_id', '=', $request->user
			]
		])->firstOrFail();

		Job::findOrFail($request->job)->firm->notify(new NewJobApplication($job_application));

		return $job_application;
	}

	public function detachJob(Request $request) : void
	{
		$validated = $request->validate([
			'user' => 'required|integer|gt:0',
			'job' => 'required|integer|gt:0'
		]);
		User::findOrFail($validated['user'])->jobs()->detach($validated['job']);
	}

}
