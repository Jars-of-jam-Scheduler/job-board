<?php

namespace App\Http\Requests;

use App\Models\JobUser;

use Illuminate\Foundation\Http\FormRequest;

class AcceptOrRefuseJobApplicationUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('accept-or-refuse-job-application', $this->route()->parameter('job_application'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
		return [
			'accept_or_refuse' => 'required|boolean',
			'firm_message' => 'required|string',
        ];
    }
}
