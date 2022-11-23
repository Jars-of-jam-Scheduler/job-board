<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable|string',

			'job' => 'nullable|array:id,attach_or_detach,message|required_array_keys:id,attach_or_detach',
			'job.id' => 'integer|gt:0',
			'job.attach_or_detach' => 'boolean',
			'job.message' => 'required_if:job.attach_or_detach,true|string',

			'job_application' => 'nullable|array:id,accept_or_refuse,firm_message|required_array_keys:id,accept_or_refuse',
			'job_application.id' => 'integer|gt:0',
			'job_application.accept_or_refuse' => 'boolean',
			'job_application.firm_message' => 'required_if:job_application.accept_or_refuse,true|string',
        ];
    }
}
