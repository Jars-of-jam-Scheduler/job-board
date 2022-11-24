<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role, JobUser};
use App\Notifications\AcceptedJobApplication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;

class AcceptJobApplication extends TestCase
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
	
		$this->actingAs($this->applier, 'sanctum')->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);

		Sanctum::actingAs($firm);
	}

    public function test_job_accept_status()
    {
		$job_application = JobUser::where([
			['job_id', $this->job['id']],
			['user_id', $this->applier['id']],
		])->firstOrFail();

        $response = $this->post('/api/users/accept_or_refuse_job_application', [
			'job_application_id' => $job_application['id'],
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => true, 
		]);
        $response->assertStatus(201);
    }

	public function test_job_accept_data()
	{
		$job_application = JobUser::where([
			['job_id', $this->job['id']],
			['user_id', $this->applier['id']],
		])->firstOrFail();
		
        $response = $this->post('/api/users/accept_or_refuse_job_application', [
			'job_application_id' => $job_application['id'],
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => true, 
		]);
        $this->assertDatabaseHas('jobs_apps_approvals', [
			'id' => $response->json('id'), 
			'job_application_id' => $job_application['id'],
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accepted_or_refused' => true,
		]);
	}

	public function test_job_accept_notification_sent()
	{
		$job_application = JobUser::where([
			['job_id', $this->job['id']],
			['user_id', $this->applier['id']],
		])->firstOrFail();
		
        $response = $this->post('/api/users/accept_or_refuse_job_application', [
			'job_application_id' => $job_application['id'],
			'firm_message' => 'The message the firm writes, to be read by the job applier. Both in the cases that the firm has accepted or refused the job application.',
			'accept_or_refuse' => true, 
		]);

		Notification::assertSentTo(
            [$this->applier], function(AcceptedJobApplication $notification, $channels) use ($job_application) {
				return $notification->getJobApplication()->id === $job_application->id;
			}
        );
	}
}
