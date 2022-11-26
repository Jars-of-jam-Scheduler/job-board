<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class UserJobAttachUnauthenticatedTest extends TestCase
{
	use RefreshDatabase;

	public function test_attach_user_job_status()
    {
		$response = $this->put(route('appliers.update'), [
			'job' => [
				'id' => 1,
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);
        $response->assertStatus(401);
    }
}
