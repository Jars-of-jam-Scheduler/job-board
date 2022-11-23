<?php

namespace App\Http\Requests;

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
        return true;
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
			'job_application_id' => 'required|integer|gt:0',
        ];
    }
}
