<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role, JobUser};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class RefuseJobApplicationUnauthenticatedTest extends TestCase
{
	use RefreshDatabase;

    public function test_job_refuse_status()
    {
        $response = $this->post('/api/accept_or_refuse_job_application', [
			'job_application' => 1,
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => false, 
		]);
        $response->assertStatus(401);
    }
}
