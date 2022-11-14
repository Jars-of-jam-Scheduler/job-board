<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptedRefusedJobsApplicationsHistory extends Model
{
    use HasFactory;

	protected $table = 'jobs_apps_approvals';

	protected $fillable = [
		'accepted_or_refused', 
		'firm_message',
		'job_application_id'
	];

	public function jobUser()
	{
		return $this->belongsTo(JobUser::class);
	}
}