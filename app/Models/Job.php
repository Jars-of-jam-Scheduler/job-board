<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Job extends Model
{
    use HasFactory;

	protected $table = 'firms_jobs';

	protected $fillable = [
		'title',
		'firm_id',
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

	public function users()
	{
		return $this->belongsToMany(User::class)->using(JobUser::class);
	}

	protected function title(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => ucfirst($value)
		);
	}

	public function firm()
	{
		return $this->belongsTo(User::class);
	}
}