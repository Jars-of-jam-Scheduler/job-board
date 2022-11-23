<?php

namespace Tests\Feature;

use App\Models\{Job, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class JobDeleteUnauthenticatedTest extends TestCase
{
    use RefreshDatabase;

	public function test_delete_job_status() : void
    {
        $response = $this->put(route('jobs.destroy', ['job' => 1]));
        $response->assertStatus(401);
    }
}
