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
		$response = $this->put(route('users.update', ['user' => 1]), [
			'job' => [
				'id' => 1,
				'attach_or_detach' => false,
			]
		]);
        $response->assertStatus(401);
    }

}
