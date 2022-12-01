<?php

use App\Models\User;
use App\Http\Controllers\{JobController, ApplierController, FirmController};

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
Route::put('/jobs/{job}/restore', [JobController::class, 'restore'])->whereNumber('job')->name('jobs_restore');

Route::middleware('auth:sanctum')->prefix('appliers')->name('appliers.')->group(function() {
	Route::put('/', [ApplierController::class, 'update'])->name('update');
	Route::put('/jobs/{job}/attach', [ApplierController::class, 'attachJob'])->whereNumber('job')->name('attach_job');
	Route::put('/jobs/{job}/detach', [ApplierController::class, 'detachJob'])->whereNumber('job')->name('detach_job');
});

Route::middleware('auth:sanctum')->prefix('firms')->name('firms.')->group(function() {
	Route::put('/', [FirmController::class, 'update'])->name('update');
	Route::post('/jobs_applications/{job_application}/accept_or_refuse_job_application', [FirmController::class, 'acceptOrRefuseJobApplication'])->whereNumber('job_application')->name('accept_or_refuse_job_application');
});