<?php

namespace Tests\Feature;

use App\Models\Job;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class JobUpdateTest extends TestCase
{
    use RefreshDatabase;

	private array $job_update_new_data;
	private Job $job_to_update;

	public function setUp() : void
	{
		parent::setUp();

		$this->job_to_update = Job::create([
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

		$this->job_update_new_data = [
			'title' => 'My Giga Hyper Super Job',
			'min_salary' => 80000, 
			'max_salary' => 100000, 
			'contractual_working_time' => '35',
		];
	}

    public function test_update_job_status() : void
    {
        $response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $this->job_update_new_data);
        $response->assertStatus(200);
    }

	public function test_update_job_update_data() : void
    {
        $response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $this->job_update_new_data);
        $this->assertDatabaseHas('jobs', [...$this->job_to_update->toArray(), ...$this->job_update_new_data]);
    }

}
