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
		return $this->user()->can('store-job');
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
			'working_place' =>  ['required', new Enum(WorkingPlace::class)],
			'working_place_country' => ['required', new Enum(WorkingPlaceCountry::class)],
			'employment_contract_type' => ['required', new Enum(EmploymentContractType::class)],
			'contractual_working_time' => 'required|string',
			'contractual_working_time' => 'required|string',
			'collective_agreement' => ['required', new Enum(CollectiveAgreement::class)],
			'flexible_hours' => 'required|boolean',
			'working_hours_modulation_system' => 'required|boolean'
        ];
    }
}
