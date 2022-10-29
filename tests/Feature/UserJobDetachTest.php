<?php

namespace Tests\Feature;

use App\Models\{User, Job};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserJobDetachTest extends TestCase
{
	use RefreshDatabase;

	private User $user;
	private Job $job;

	public function setUp() : void
	{
		parent::setUp();

		$this->user = User::create([
			'name' => 'Test User',
			'email' => 'test@thegummybears.test', 
			'password' => 'azerty', 
		]);

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
	}

    public function test_detach_user_job_status()
    {
        $this->post('/api/attach_user_job', [
			'user' => $this->user['id'],
			'job' => $this->job['id']
		]);

		$response = $this->post('/api/detach_user_job', [
			'user' => $this->user['id'],
			'job' => $this->job['id']
		]);

        $response->assertStatus(200);
    }

	public function test_detach_user_job_data()
    {
		$this->post('/api/attach_user_job', [
			'user' => $this->user['id'],
			'job' => $this->job['id']
		]);

		$response = $this->post('/api/detach_user_job', [
			'user' => $this->user['id'],
			'job' => $this->job['id']
		]);

        $this->assertDatabaseMissing('user_job', [
			'user' => $this->user['id'],
			'job' => $this->job['id']
		]);
    }

}
