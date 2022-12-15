<?php

namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
use App\Http\Requests\{AcceptOrRefuseJobApplicationUserRequest, UpdateFirmRequest};
use App\Http\Resources\JobResource;

use Illuminate\Support\Facades\Gate;

class FirmController extends Controller
{
	public function update(UpdateFirmRequest $request)
	{
		$authenticated_user = auth()->user();
		$authenticated_user->fill($request->validated());
		$authenticated_user->update();
		return true;
	}

	public function acceptOrRefuseJobApplication(AcceptOrRefuseJobApplicationUserRequest $request, JobUser $job_application) : AcceptedRefusedJobsApplicationsHistory
	{
		$ret = AcceptedRefusedJobsApplicationsHistory::create([
			'accepted_or_refused' => $request->accept_or_refuse, 
			'firm_message' => $request->firm_message,
			'job_application_id' => $job_application->getKey()
		]);

		if($request->accept_or_refuse) {
			$job_application->user->notify(new AcceptedJobApplication($job_application));
		} else {
			$job_application->user->notify(new RefusedJobApplication($job_application));
		}

		return $ret;
	}

	public function getJobs()
	{
		Gate::authorize('only-firm');
		return JobResource::collection(auth()->user()->firmJobs()->latest()->simplePaginate(25));
	}

	public function getSoftDeletedJobs()
	{
		Gate::authorize('only-firm');
		return JobResource::collection(auth()->user()->firmJobs()->latest()->onlyTrashed()->simplePaginate(25));
	}
}
