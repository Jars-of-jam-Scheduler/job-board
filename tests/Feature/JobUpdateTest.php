<?php

namespace Tests\Feature;

use App\Models\{Job, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class JobUpdateTest extends TestCase
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
		Sanctum::actingAs($firm);
		
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
        $response->assertStatus(200);
    }

	public function test_update_job_update_data() : void
    {
        $response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $this->job_update_new_data);
        $this->assertDatabaseHas('firms_jobs', [...$this->job_to_update->toArray(), ...$this->job_update_new_data]);
    }

	public function test_update_job_with_firm_status() : void
    {
        $response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $this->job_with_firm_update_new_data);
        $response->assertSessionHasErrors(['firm_id']);
    }

	public function test_update_job_with_firm_update_data_missing() : void
    {
        $response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $this->job_with_firm_update_new_data);
        $this->assertDatabaseMissing('firms_jobs', [...$this->job_to_update->toArray(), ...$this->job_with_firm_update_new_data]);
    }

	public function test_update_job_with_firm_update_data_exists() : void
    {
        $response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $this->job_with_firm_update_new_data);
        $this->assertDatabaseHas('firms_jobs', $this->job_to_update->toArray());
    }

	/**
     * @dataProvider badDataProvider
     */
	public function test_bad_data(
		$id,
		$title, 
		$firm_id,
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

		if(isset($firm_id)) {
			$data_to_send['firm_id'] = $firm_id;
		} elseif(isset($id)) {
			$data_to_send['id'] = $id;
		}
		
		$response = $this->put(route('jobs.update', ['job' => $this->job_to_update['id']]), $data_to_send);

		if(isset($id)) {
			unset($data_to_send['id']);
			$this->assertDatabaseMissing('firms_jobs', [
				'id' => $id,
				... $data_to_send
			])->assertDatabaseHas('firms_jobs', [
				'id' => $this->job_to_update['id'],
				... $data_to_send
			]);
		} else {
			$response->assertSessionHasErrors($expected_result);	
		}
	}

	public function badDataProvider() : array
	{
		return [
			[
				'id' => null,
				'title' => null,
				'firm_id' => 5,
				'presentation' => null,
				'min_salary' => null,
				'max_salary' => null,
				'working_place' => null,
				'working_place_country' => null,
				'employment_contract_type' => null,
				'contractual_working_time' => null,
				'collective_agreement' => null,
				'flexible_hours' => null,
				'working_hours_modulation_system' => null,
				'expected_result' => ['firm_id'],
			],
			[
				'id' => 999,
				'title' => null,
				'firm_id' => null,
				'presentation' => null,
				'min_salary' => null,
				'max_salary' => null,
				'working_place' => null,
				'working_place_country' => null,
				'employment_contract_type' => null,
				'contractual_working_time' => null,
				'collective_agreement' => null,
				'flexible_hours' => null,
				'working_hours_modulation_system' => null,
				'expected_result' => ['id'],
			]
		];
	}
}
