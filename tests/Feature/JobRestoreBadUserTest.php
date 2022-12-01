<?php

namespace Tests\Feature;

use App\Models\{Job, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class JobRestoreBadUserTest extends TestCase
{
	use RefreshDatabase;

	private Job $job_to_delete;

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
		Sanctum::actingAs($firm);

		$applier = User::create([
			'name' => 'The Applier',
			'email' => 'test@thegummybears2.test', 
			'password' => 'azerty', 
		]);
		$applier->roles()->save(Role::findOrFail('job_applier'));
		Sanctum::actingAs($applier);

		$this->job_to_delete = Job::create([
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
		$this->job_to_delete->delete();
	}

    public function test_restore_job_status()
    {
		$response = $this->put(route('jobs_restore', ['job' => $this->job_to_delete['id']]));
        $response->assertStatus(403);
    }
}
