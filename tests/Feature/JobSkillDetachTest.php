<?php

namespace Tests\Feature;

use App\Models\{Job, Skill};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobSkillDetachTest extends TestCase
{

	private Job $job;
	private Skill $skill;

	public function setUp() : void
	{
		parent::setUp();

		$this->job = Job::create([
			'title' => 'My Super Job',
			'presentation' => 'Its presentation', 
			'min_salary' => 45000, 
			'max_salary' => 45000, 
			'working_place' => 'full_remote', 
			'working_place_country' => 'fr',
			'employment_contract_type' => 'cdi', 
			'contractual_working_time' => '39',
			'collective_agreement' => 'syntec', 
			'flexible_hours' => true, 
			'working_hours_modulation_system' => true
		]);

		$this->skill = Skill::create([
			'title' => 'Laravel'
		]);
	}

    public function test_detach_job_skill_status()
    {
        $this->post('/api/attach_job_skill', [
			'job' => $this->job['id'], 
			'skill' => $this->skill['id'], 
		]);

		$response = $this->post('/api/detach_job_skill', [
			'job' => $this->job['id'], 
			'skill' => $this->skill['id'], 
		]);

        $response->assertStatus(200);
    }

	public function test_detach_job_skill_data()
    {
		$this->post('/api/attach_job_skill', [
			'job' => $this->job['id'], 
			'skill' => $this->skill['id'], 
		]);

		$response = $this->post('/api/detach_job_skill', [
			'job' => $this->job['id'], 
			'skill' => $this->skill['id'], 
		]);

        $this->assertDatabaseMissing('job_skill', [
			'job_id' => $this->job['id'], 
			'skill_id' => $this->skill['id'], 
		]);
    }
}
