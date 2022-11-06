<?php

namespace App\Http\Controllers;

use App\Models\{Job, User};

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
			'message' => $validated['message']
		]);
	}

	public function detachJob(Request $request) : void
	{
		Gate::authorize('detach-job');

		$validated = $request->validate([
			'user' => 'required|integer|gt:0',
			'job' => 'required|integer|gt:0'
		]);
		User::findOrFail($validated['user'])->jobs()->detach($validated['job']);
	}

}
