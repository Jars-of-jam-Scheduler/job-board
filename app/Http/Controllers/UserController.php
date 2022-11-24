<?php
namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
use App\Http\Requests\{AcceptOrRefuseJobApplicationUserRequest, UpdateUserRequest};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
	public function update(UpdateUserRequest $request)
	{
		if($request->has('job')) {
			$this->attachOrDetachJob($request);
		}
		
		$authenticated_user = auth()->user();
		$authenticated_user->fill($request->validated());
		$authenticated_user->update();
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

	public function acceptOrRefuseJobApplication(AcceptOrRefuseJobApplicationUserRequest $request) : AcceptedRefusedJobsApplicationsHistory
	{
		$job_application = JobUser::findOrFail($request->job_application_id);

		Gate::authorize('accept-or-refuse-job-application', $job_application);

		$ret = AcceptedRefusedJobsApplicationsHistory::create([
			'accepted_or_refused' => $request->accept_or_refuse, 
			'firm_message' => $request->firm_message,
			'job_application_id' => $request->job_application_id
		]);

		if($request->accept_or_refuse) {
			$job_application->user->notify(new AcceptedJobApplication($job_application));
		} else {
			$job_application->user->notify(new RefusedJobApplication($job_application));
		}

		return $ret;
	}
}
