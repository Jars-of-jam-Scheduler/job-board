<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role, JobUser, AcceptedRefusedJobsApplicationsHistory};
use App\Notifications\AcceptedJobApplication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;

class AcceptAndRefuseJobApplicationTest extends TestCase
{
	use RefreshDatabase;

	private User $applier;
	private Job $job;

	public function setUp() : void
	{
		parent::setUp();

		Notification::fake();

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

		Sanctum::actingAs($firm);
	}

	public function test_job_accept_refuse_status()
    {
		$job_application = JobUser::where([
			['job_id', $this->job['id']],
			['user_id', $this->applier['id']],
		])->firstOrFail();

		$this->post(route('firms.accept_or_refuse_job_application', [
			'job_application' => $job_application['id'],
		]), [
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => true, 
		]);
		
		$response = $this->post(route('firms.accept_or_refuse_job_application', [
			'job_application' => $job_application['id'],
		]), [
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => false, 
		]);
        $response->assertStatus(201);
    }

	public function test_job_accept_refuse_data()
    {
		$job_application = JobUser::where([
			['job_id', $this->job['id']],
			['user_id', $this->applier['id']],
		])->firstOrFail();

		$first_response = $this->post(route('firms.accept_or_refuse_job_application', [
			'job_application' => $job_application['id'],
		]), [
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => true, 
		]);

		$last_response = $this->post(route('firms.accept_or_refuse_job_application', [
			'job_application' => $job_application['id'],
		]), [
			'firm_message' => 'The second message.',
			'accept_or_refuse' => false, 
		]);

        $this->assertDatabaseHas('jobs_apps_approvals', [
			'id' => $first_response->json('id'), 
			'job_application_id' => $job_application['id'],
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accepted_or_refused' => true,
		])->assertDatabaseHas('jobs_apps_approvals', [
			'id' => $last_response->json('id'), 
			'job_application_id' => $job_application['id'],
			'firm_message' => 'The second message.',
			'accepted_or_refused' => false,
		]);
	}
}
