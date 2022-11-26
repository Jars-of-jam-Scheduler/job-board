<?php

namespace App\Http\Controllers;

use App\Models\{Job, User, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\{NewJobApplication, AcceptedJobApplication, RefusedJobApplication};
use App\Http\Requests\{AcceptOrRefuseJobApplicationUserRequest, UpdateUserRequest};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FirmController extends UserController
{
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
