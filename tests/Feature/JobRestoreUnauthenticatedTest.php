<?php

namespace Tests\Feature;

use App\Models\{Job, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class JobRestoreUnauthenticatedTest extends TestCase
{
	use RefreshDatabase;

    public function test_restore_job_status()
    {
		$response = $this->put(route('jobs_restore', ['job' => 1]));
        $response->assertStatus(401);
    }
}
