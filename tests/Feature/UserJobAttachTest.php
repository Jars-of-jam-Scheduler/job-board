<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class UserJobAttachTest extends TestCase
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
	}

    public function test_attach_user_job_status()
    {
        $response = $this->post('/api/attach_user_job', [
			'user' => $this->applier['id'],
			'job' => $this->job['id'],
			'message' => 'The message the applicant writes, to be read by the firm he applies for.'
		]);

        $response->assertStatus(200);
    }

	public function test_attach_user_job_data()
    {
		$this->post('/api/attach_user_job', [
			'user' => $this->applier['id'],
			'job' => $this->job['id'],
			'message' => 'The message the applicant writes, to be read by the firm he applies for.'
		]);

        $this->assertDatabaseHas('job_user', [
			'user_id' => $this->applier['id'],
			'job_id' => $this->job['id'],
			'message' => 'The message the applicant writes, to be read by the firm he applies for.'
		]);
    }

	public function test_attach_user_job_once_status()
	{
		$this->post('/api/attach_user_job', [
			'user' => $this->applier['id'],
			'job' => $this->job['id'],
			'message' => 'The message the applicant writes, to be read by the firm he applies for.'
		]);

		$response = $this->post('/api/attach_user_job', [
			'user' => $this->applier['id'],
			'job' => $this->job['id'],
			'message' => 'The message the applicant writes, to be read by the firm he applies for.'
		]);

		$response->assertStatus(400);

	}

	public function test_attach_user_job_once_data()
	{
		$this->post('/api/attach_user_job', [
			'user' => $this->applier['id'],
			'job' => $this->job['id'],
			'message' => 'The message the applicant writes, to be read by the firm he applies for.'
		]);

		$response = $this->post('/api/attach_user_job', [
			'user' => $this->applier['id'],
			'job' => $this->job['id'],
			'message' => 'The message the applicant writes, to be read by the firm he applies for.'
		]);

		$inserted_jobs_counter = User::findOrFail($this->applier['id'])->jobs()->where('job_id', $this->job['id'])->count();
		$this->assertEquals($inserted_jobs_counter, 1);
	}
}
