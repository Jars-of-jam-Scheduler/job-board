<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'firm_id' => $this->firm_id,
			'presentation' => $this->presentation,
			'min_salary' => $this->min_salary,
			'max_salary' => $this->max_salary,
			'working_place' => $this->working_place,
			'working_place_country' => $this->working_place_country,
			'employment_contract_type' => $this->employment_contract_type,
			'contractual_working_time' => $this->contractual_working_time,
			'collective_agreement' => $this->collective_agreement,
			'flexible_hours' => $this->flexible_hours,
			'working_hours_modulation_system' => $this->working_hours_modulation_system,
		];
	}
}
