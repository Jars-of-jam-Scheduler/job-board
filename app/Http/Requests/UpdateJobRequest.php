<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\Request;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update-job-firm', $this->job);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        return [
            'title' => 'nullable|string', 
			'firm_id' => 'prohibited',
			'presentation' => 'nullable|string', 
			'min_salary' => 'nullable|integer', 
			'max_salary' => 'nullable|integer',
			'working_place' =>  ['nullable', new Enum(WorkingPlace::class)],
			'working_place_country' => ['nullable', new Enum(WorkingPlaceCountry::class)],
			'employment_contract_type' => ['nullable', new Enum(EmploymentContractType::class)],
			'contractual_working_time' => 'nullable|string',
			'contractual_working_time' => 'nullable|string',
			'collective_agreement' => ['nullable', new Enum(CollectiveAgreement::class)],
			'flexible_hours' => 'nullable|boolean',
			'working_hours_modulation_system' => 'nullable|boolean',

			'skill' => 'nullable|array:id,attach_or_detach|required_array_keys:id,attach_or_detach',
			'skill.id' => 'integer|gt:0',
			'skill.attach_or_detach' => 'boolean'
		];
    }
}
