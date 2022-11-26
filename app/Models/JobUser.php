<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class JobUser extends Pivot
{
	use HasFactory;

	protected $fillable = [
		'job_id',
		'user_id',
		'message'
	];

	public $incrementing = true;

	public function acceptedOrRefusedJobApplications()
	{
		return $this->hasMany(AcceptedRefusedJobsApplicationsHistory::class);
	}

	public function job()
	{
		return $this->belongsTo(Job::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
