<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role, JobUser};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class UserJobDetachTest extends TestCase
{
	use RefreshDatabase;

	private User $applier;
	private Job $job;

	public function setUp() : void
	{
		parent::setUp();

		Role::create([
			'title' => 'firm'
		]);
		Role::create([
			'title' => 'job_applier'
		]);

		$this->applier = User::create([
			'name' => 'Test User',
			'email' => 'testapplier@thegummybears.test', 
			'password' => 'azerty', 
		]);
		$this->applier->roles()->save(Role::findOrFail('job_applier'));
		Sanctum::actingAs($this->applier);

		$firm = User::create([
			'name' => 'Test User',
			'email' => 'testfirm@thegummybears.test', 
			'password' => 'azerty', 
		]);
		$firm->roles()->save(Role::findOrFail('firm'));

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

		JobUser::create([
			'job_id' => $this->job['id'],
			'user_id' => $this->applier['id'],
			'message' => 'I want to apply for this job because foobar.'
		]);
	}

    public function test_detach_user_job_status()
    {
		$response = $this->put(route('appliers.detach_job', [
			'job' => $this->job['id'],
		]));
        $response->assertStatus(200);
    }

	public function test_detach_user_job_data()
    {
		$this->put(route('appliers.detach_job', [
			'job' => $this->job['id'],
		]));

        $this->assertDatabaseMissing('job_user', [
			'user_id' => $this->applier['id'],
			'job_id' => $this->job['id']
		]);
    }
}