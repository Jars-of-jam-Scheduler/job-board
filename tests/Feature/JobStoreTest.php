<?php

namespace Tests\Feature;

use App\Enums\{WorkingPlace, CollectiveAgreement, WorkingPlaceCountry, EmploymentContractType};
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

	/**
     * @dataProvider badDataProvider
     */
	public function test_bad_data(
		$id,
		$title, 
		$presentation, 
		$min_salary, 
		$max_salary,
		$working_place,
		$working_place_country,
		$employment_contract_type,
		$contractual_working_time,
		$collective_agreement,
		$flexible_hours,
		$working_hours_modulation_system,
		$expected_result
	)
	{
		$data_to_send = [
			'id' => $id,
			'title' => $title, 
			'presentation' => $presentation, 
			'min_salary' => $min_salary, 
			'max_salary' => $max_salary,
			'working_place' =>  $working_place,
			'working_place_country' => $working_place_country,
			'employment_contract_type' => $employment_contract_type,
			'contractual_working_time' => $contractual_working_time,
			'collective_agreement' => $collective_agreement,
			'flexible_hours' => $flexible_hours,
			'working_hours_modulation_system' => $working_hours_modulation_system,
		];

        $response = $this->post(route('jobs.store'), $data_to_send);

		unset($data_to_send['id']);

		$this->assertDatabaseMissing('firms_jobs', [
			'id' => $id,
			... $data_to_send
		])->assertDatabaseHas('firms_jobs', [
			'id' => $response['id'],
			... $data_to_send
		]);
	}

	public function badDataProvider() : array
	{
		return [
			[
				'id' => 999,
				'title' => 'test',
				'presentation' => 'test',
				'min_salary' => 12,
				'max_salary' => 122,
				'working_place' => 'hybrid_remote',
				'working_place_country' => 'fr',
				'employment_contract_type' => 'cdi',
				'contractual_working_time' => 'test',
				'collective_agreement' => 'syntec',
				'flexible_hours' => false,
				'working_hours_modulation_system' => 0,
				'expected_result' => ['id'],
			]
		];
	}
}