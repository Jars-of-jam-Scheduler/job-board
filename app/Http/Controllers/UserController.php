<?php

namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{AcceptedJobApplication, RefusedJobApplication};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    
	public function attachJob(Request $request) : void
	{
		Gate::authorize('attach-job');

		$validated = $request->validate([
			'user' => 'required|integer|gt:0',
			'job' => 'required|integer|gt:0',
			'message' => 'nullable|string'
		]);

		$user = User::findOrFail($validated['user']);

		abort_if($user->hasAppliedFor($validated['job']), 400, __('You have already applied for that job.'));

		$user->jobs()->attach($validated['job'], [
			'message' => $validated['message'] ?? ''
		]);
	}

	public function detachJob(Request $request) : void
	{
		$validated = $request->validate([
			'user' => 'required|integer|gt:0',
			'job' => 'required|integer|gt:0'
		]);

		Gate::authorize('detach-job', Job::findOrFail($request->job));

		User::findOrFail($validated['user'])->jobs()->detach($validated['job']);
	}

	public function acceptOrRefuseJobApplication(Request $request) : AcceptedRefusedJobsApplicationsHistory
	{
		$request->validate([
			'job_application' => 'required|integer|gt:0',
			'firm_message' => 'required|string', 
			'accept_or_refuse' => 'required|boolean'
		]);

		$job_application = JobUser::findOrFail($request->job_application);

		Gate::authorize('accept-job-application', $job_application);

		$new_job_application_accept_or_refuse = AcceptedRefusedJobsApplicationsHistory::create([
			'accepted_or_refused' => $request->accept_or_refuse, 
			'firm_message' => $request->firm_message,
			'job_application_id' => $job_application->id
		]);

		if($request->accept_or_refuse) {
			$job_application->user->notify(new AcceptedJobApplication($job_application));

		} else {
			$job_application->user->notify(new RefusedJobApplication($job_application));

		}

		return $new_job_application_accept_or_refuse;
	}
}
