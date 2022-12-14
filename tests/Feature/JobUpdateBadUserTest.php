<?php

namespace Tests\Feature;

use App\Models\{Job, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class JobUpdateBadUserTest extends TestCase
{
    use RefreshDatabase;

	private array $job_update_new_data;
	private Job $job_to_update;
	private array $job_with_firm_update_new_data;

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

		$this->job_to_update = Job::create([
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

		$this->job_update_new_data = [
			'title' => 'My Giga Hyper Super Job',
			'min_salary' => 80000, 
			'max_salary' => 100000, 
			'contractual_working_time' => '35',
		];

		$this->job_with_firm_update_new_data = [
			'firm_id' => 2,
			'title' => 'My Giga Hyper Super Job',
			'min_salary' => 80000, 
			'max_salary' => 100000, 
			'contractual_working_time' => '35',
		];
	}

    public function test_update_job_status() : void
    {
        $response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $this->job_update_new_data);
        $response->assertStatus(403);
    }
}
