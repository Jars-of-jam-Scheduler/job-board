<?php

namespace Tests\Feature;

use App\Models\{User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class JobStoreTest extends TestCase
{
    use RefreshDatabase;

	private array $job;

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
		Sanctum::actingAs($firm);

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

	public function test_store_job_storage_data() : void
    {
        $response = $this->post(route('jobs.store'), $this->job);
        $this->assertDatabaseHas('firms_jobs', ['id' => $response->json('id'), ...$this->job]);
    }
}