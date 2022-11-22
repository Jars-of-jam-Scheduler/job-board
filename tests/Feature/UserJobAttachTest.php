<?php

namespace Tests\Feature;

use App\Models\{User, Job, Role, JobUser};
use App\Notifications\NewJobApplication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Notification;

class UserJobAttachTest extends TestCase
{
	use RefreshDatabase;

	private User $applier, $firm;
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
		Sanctum::actingAs($this->applier);

		$this->firm = User::create([
			'name' => 'Test User',
			'email' => 'testfirm@thegummybears.test', 
			'password' => 'azerty', 
		]);
		$this->firm->roles()->save(Role::findOrFail('firm'));

		$this->job = Job::create([
			'title' => 'My Super Job',
			'firm_id' => $this->firm->getKey(),
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
		$response = $this->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);

        $response->assertStatus(200);
    }

	public function test_attach_user_job_data()
    {
		$this->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);

        $this->assertDatabaseHas('job_user', [
			'user_id' => $this->applier['id'],
			'job_id' => $this->job['id'],
			'message' => 'I want to apply for this job because foobar.'
		]);
    }

	public function test_attach_user_job_once_status()
	{
		$this->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);

		$response = $this->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);

		$response->assertStatus(400);
	}

	public function test_attach_user_job_once_data()
	{
		$this->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);

		$this->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);

		$inserted_jobs_counter = auth()->user()->jobs()->where('job_id', $this->job['id'])->count();
		$this->assertEquals($inserted_jobs_counter, 1);
	}

	public function test_attach_user_job_notification_sent()
	{
		$this->put(route('users.update', ['user' => $this->applier['id']]), [
			'job' => [
				'id' => $this->job['id'],
				'attach_or_detach' => true,
				'message' => 'I want to apply for this job because foobar.'
			]
		]);
		$job_application = auth()->user()->jobs()->where('job_id', $this->job['id'])->firstOrFail();

		Notification::assertSentTo(
            [$this->firm], function(NewJobApplication $notification, $channels) use ($job_application) {
				return $notification->getJobApplication()->id === $job_application['id'];
			}
        );
	}
}
