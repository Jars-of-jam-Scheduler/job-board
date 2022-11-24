<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role, JobUser};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AcceptJobApplicationUnauthenticated extends TestCase
{
	use RefreshDatabase;

    public function test_job_accept_status()
    {
        $response = $this->post('/api/users/accept_or_refuse_job_application', [
			'job_application_id' => 1,
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => true, 
		]);
        $response->assertStatus(401);
    }
}
