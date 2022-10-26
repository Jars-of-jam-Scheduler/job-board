<?php

namespace App\Http\Requests;

use App\Enums\WorkingPlace;
use App\Enums\WorkingPlaceCountry;
use App\Enums\EmploymentContractType;
use App\Enums\CollectiveAgreement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class StoreJobRequest extends FormRequest
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
            'title' => 'required|string', 
			'presentation' => 'required|string', 
			'min_salary' => 'required|integer', 
			'max_salary' => 'required|integer',
			'working_place' =>  [new Enum(WorkingPlace::class)],
			'working_place_country' => new Enum(WorkingPlaceCountry::class),
			'employment_contract_type' => new Enum(EmploymentContractType::class),
			'contractual_working_time' => 'required|string',
			'contractual_working_time' => 'required|string',
			'collective_agreement' => new Enum(CollectiveAgreement::class),
			'flexible_hours' => 'boolean',
			'working_hours_modulation_system' => 'boolean'
        ];
    }
}
