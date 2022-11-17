<?php

use App\Models\User;
use App\Http\Controllers\{JobController, UserController};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/sanctum/token', function(Request $request) {
	$request->validate([
		'email' => 'required|email',
		'password' => 'required',
		'device_name' => 'required'
	]);

	$user = User::where('email', $request->email)->first();
	if(!$user || !Hash::check($request->password, $user->password)) {
		throw ValidationException::withMessage([
			'email' => 'The provided credentials are incorrect.'
		]);
	}

	return $user->createToken($request->device_name)->plainTextToken;
});

Route::apiResource('jobs', JobController::class);  // Routes are Sanctumed in the controller

Route::middleware('auth:sanctum')->group(function() {

	Route::put('/restore_job/{job_id}', [JobController::class, 'restore'])->name('jobs_restore');

	Route::post('/attach_job_skill', [JobController::class, 'attachJobSkill']);
	Route::post('/detach_job_skill', [JobController::class, 'detachJobSkill']);
	
	Route::post('/attach_user_job', [UserController::class, 'attachJob']);
	Route::post('/detach_user_job', [UserController::class, 'detachJob']);

	Route::post('/accept_or_refuse_job_application', [UserController::class, 'acceptOrRefuseJobApplication']);

	Route::get('/user', function (Request $request) {
		return $request->user();
	});
	
});



