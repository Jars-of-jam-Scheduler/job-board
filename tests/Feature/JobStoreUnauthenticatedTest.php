<?php

namespace Tests\Feature;

use App\Models\{User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class JobStoreUnauthenticatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_job_status() : void
    {
        $response = $this->post(route('jobs.store'), []);
        $response->assertStatus(401);
    }
}
