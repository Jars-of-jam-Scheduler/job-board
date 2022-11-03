<?php

namespace Tests\Feature;

use App\Models\{Job, User};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class JobDeleteTest extends TestCase
{
    use RefreshDatabase;

	private Job $job_to_delete;

	public function setUp() : void
	{
		parent::setUp();

		$firm = User::create([
			'name' => 'The Firm',
			'email' => 'test@thegummybears.test', 
			'password' => 'azerty', 
		]);

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
	}

    public function test_delete_job_status() : void
    {
        $response = $this->put(route('jobs.destroy', ['job' => $this->job_to_delete['id']]));
        $response->assertStatus(200);
    }

	public function test_delete_job_deletion_data() : void
    {
        $response = $this->delete(route('jobs.destroy', ['job' => $this->job_to_delete['id']]));
		//$response->dd();
        $this->assertDatabaseMissing('jobs', ['id' => $this->job_to_delete['id']]);
    }

}
