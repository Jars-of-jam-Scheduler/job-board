<?php

namespace Tests\Feature;

use App\Models\{Job, Skill, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class JobSkillDetachUnauthenticatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_detach_job_skill_status()
    {
		$response = $this->put(route('jobs.update', ['job' => 1]), [
			'skill' => [
				'id' => 1,
				'attach_or_detach' => false
			]
		]);
        $response->assertStatus(401);
    }
}
