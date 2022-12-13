<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\{WorkingPlace, WorkingPlaceCountry, EmploymentContractType, CollectiveAgreement};

class EnumController extends Controller
{
    public function get()
	{
		return [
			'working_place' => collect(WorkingPlace::cases())->map(function ($enum) {
				return ['value' => $enum->value, 'label' => __('working_place.' . $enum->value)];
			}),
			'working_place_country' => collect(WorkingPlaceCountry::cases())->map(function ($enum) {
				return ['value' => $enum->value, 'label' => __('working_place_country.' . $enum->value)];
			}),
			'employment_contract_type' => collect(EmploymentContractType::cases())->map(function ($enum) {
				return ['value' => $enum->value, 'label' => __('employment_contract_type.' . $enum->value)];
				}),
			'collective_agreement' => collect(CollectiveAgreement::cases())->map(function ($enum) {
				return ['value' => $enum->value, 'label' =>  __('collective_agreement.' . $enum->value)];
			}),
		];
	}

}
