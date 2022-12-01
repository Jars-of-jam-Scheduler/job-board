<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class UserJobDetachUnauthenticatedTest extends TestCase
{
	use RefreshDatabase;

    public function test_detach_user_job_status()
    {
		$response = $this->put(route('appliers.detach_job', [
			'job' => 1,
		]));
        $response->assertStatus(401);
    }

}
