<?php
namespace App\Http\Controllers;

use App\Http\Requests\{AcceptOrRefuseJobApplicationUserRequest, UpdateUserRequest};

class UserController extends Controller
{
	public function update(UpdateUserRequest $request)
	{
		$authenticated_user = auth()->user();
		$authenticated_user->fill($request->validated());
		$authenticated_user->update();
		return true;
	}
}
