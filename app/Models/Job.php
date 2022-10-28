<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

	protected $fillable = [
		'title',
		'presentation', 
		'min_salary', 
		'max_salary', 
		'working_place', 
		'working_place_country',
		'employment_contract_type', 
		'contractual_working_time',
		'collective_agreement', 
		'flexible_hours', 
		'working_hours_modulation_system'
	];

	public function skills()
	{
		return $this->belongsToMany(Skill::class);
	}

}
