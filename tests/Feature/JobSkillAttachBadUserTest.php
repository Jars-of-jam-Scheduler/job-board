<?php

namespace Tests\Feature;

use App\Models\{Job, Skill, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class JobSkillAttachBadUserTest extends TestCase
{
    use RefreshDatabase;

	private Job $job;
	private Skill $skill;

	public function setUp() : void
	{
		parent::setUp();

		Role::create([
			'title' => 'firm'
		]);
		Role::create([
			'title' => 'job_applier'
		]);

		$firm = User::create([
			'name' => 'The Firm',
			'email' => 'test@thegummybears.test', 
			'password' => 'azerty', 
		]);
		$firm->roles()->save(Role::findOrFail('firm'));

		$applier = User::create([
			'name' => 'The Applier',
			'email' => 'test@thegummybears2.test', 
			'password' => 'azerty', 
		]);
		$applier->roles()->save(Role::findOrFail('job_applier'));
		Sanctum::actingAs($applier);

		$this->job = Job::create([
			'title' => 'My Super Job',
			'firm_id' => $firm->getKey(),
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

    public function test_attach_job_skill_status()
    {
		$response = $this->put(route('jobs.update', ['job' => $this->job['id']]), [
			'skill' => [
				'id' => $this->skill['id'],
				'attach_or_detach' => true
			]
		]);
        $response->assertStatus(403);
    }
}
