<?php

namespace Tests\Feature;

use App\Models\{Job, Skill, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class JobSkillAttachUnauthenticatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_attach_job_skill_status()
    {
        $response = $this->put(route('jobs.update', ['job' => 1]), [
			'skill' => [
				'id' => 1,
				'attach_or_detach' => true
			]
		]);
        $response->assertStatus(401);
    }
}
