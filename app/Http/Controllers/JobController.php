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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
		Gate::authorize('store-job');

		abort_if(!User::where('id', $request->firm_id)->exists(), 400, 'The firm was not found.');
        return Job::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        //
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
		Gate::authorize('update-job-firm', $job);

		if($request->has('skill')) {
			$this->attachOrDetachJobSkill($request, $job);
		}
		
		$job->fill($request->validated());
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

	public function attachOrDetachJobSkill(Request $request, Job $job)
	{
		if($request->input('skill.attach_or_detach')) {
			$job->skills()->attach($request->input('skill.id'));
		} else {
			$job->skills()->detach($request->input('skill.id'));
		}
	}
}
