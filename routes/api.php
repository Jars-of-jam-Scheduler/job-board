<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JobController;

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

Route::apiResource('jobs', JobController::class);

Route::post('/attach_job_skill', [JobController::class, 'attachJobSkill']);
Route::post('/detach_job_skill', [JobController::class, 'detachJobSkill']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

