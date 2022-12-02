<?php

namespace App\Http\Requests;

use App\Models\Job;

use Illuminate\Foundation\Http\FormRequest;

class AttachJobApplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
	{
		return $this->user()->can('attach-job');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
		return [
			'message' => 'string',
		];
    }
}
