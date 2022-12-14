<?php

namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
use App\Http\Requests\{AcceptOrRefuseJobApplicationUserRequest, UpdateFirmRequest};
use App\Http\Resources\JobResource;

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
		return JobResource::collection(auth()->user()->firmJobs()->simplePaginate(25));
	}

	public function getSoftDeletedJobs()
	{
		return JobResource::collection(auth()->user()->firmJobs()->onlyTrashed()->simplePaginate(25));
	}
}
