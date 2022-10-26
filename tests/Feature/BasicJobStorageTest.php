<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class BasicJobStorageTest extends TestCase
{
    use RefreshDatabase;

	private $job;

	public function setUp() : void
	{
		parent::setUp();

		$this->job = [
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
		];
	}

    public function test_store_job_status() : void
    {
        $response = $this->post(route('jobs.store'), $this->job);
        $response->assertStatus(201);
    }

	public function test_store_job_stored_data() : void
    {
        $this->post(route('jobs.store'), $this->job);
        $this->assertDatabaseHas('jobs', $this->job);
    }

	public function test_store_job_stored_id() : void
    {
        $response = $this->post(route('jobs.store'), $this->job);
		Log::info('job_id');
		Log::info($response->json('id'));
        $this->assertDatabaseHas('jobs', ['id' => $response->json('id')]);
    }
}
