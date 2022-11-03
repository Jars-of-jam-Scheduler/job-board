<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateJobRequest extends FormRequest
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
			'working_hours_modulation_system' => 'nullable|boolean'
        ];
    }
}
