<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\{Job, User};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JobController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth:sanctum')->except('index');
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
        return Job::create(['firm_id' => auth()->user()->id, ...$request->validated()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJobRequest  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJobRequest $request, Job $job)
    {
		if($request->has('skill')) {
			$this->attachOrDetachJobSkill($request, $job);
		}
		
		$job->fill(['firm_id' => auth()->user()->id, ...$request->validated()]);
		$job->update();
		return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
		Gate::authorize('destroy-job', $job);
        return $job->delete();
    }

	public function restore(int $job_id)
	{
		$job = Job::withTrashed()->findOrFail($job_id);
		Gate::authorize('restore-job-firm', $job);
		return $job->restore();
	}

	private function attachOrDetachJobSkill(Request $request, Job $job)
	{
		if($request->input('skill.attach_or_detach')) {
			$job->skills()->attach($request->input('skill.id'));
		} else {
			$job->skills()->detach($request->input('skill.id'));
		}
	}
}
