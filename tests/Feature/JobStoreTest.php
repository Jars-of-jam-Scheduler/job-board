<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class JobStoreTest extends TestCase
{
    use RefreshDatabase;

	private array $job;
	private array $job_with_missing_firm;

	public function setUp() : void
	{
		parent::setUp();

		$firm = User::create([
			'name' => 'The Firm',
			'email' => 'test@thegummybears.test', 
			'password' => 'azerty', 
		]);;

		$this->job = [
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
		];

		$this->job_with_missing_firm = [
			'title' => 'My Super Job With Missing Firm',
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

	public function test_store_job_storage_data() : void
    {
        $response = $this->post(route('jobs.store'), $this->job);
        $this->assertDatabaseHas('jobs', ['id' => $response->json('id'), ...$this->job]);
    }

	public function test_store_job_missing_firm_status() : void
	{
		$response = $this->post(route('jobs.store'), $this->job_with_missing_firm);
        $response->assertStatus(400);
	}

	public function test_store_job_missing_firm_data() : void
	{
		$response = $this->post(route('jobs.store'), $this->job_with_missing_firm);
        $this->assertDatabaseMissing('jobs', [$this->job_with_missing_firm]);
	}

}
