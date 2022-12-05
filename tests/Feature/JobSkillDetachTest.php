<?php

namespace Tests\Feature;

use App\Models\{Job, Skill, User, Role};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class JobSkillDetachTest extends TestCase
{
    use RefreshDatabase;

	private Job $job;
	private Skill $skill;
	private User $firm;

	public function setUp() : void
	{
		parent::setUp();

		Role::create([
			'title' => 'firm'
		]);
		Role::create([
			'title' => 'job_applier'
		]);
		
		$this->firm = User::create([
			'name' => 'The Firm',
			'email' => 'test@thegummybears.test', 
			'password' => 'azerty', 
		]);
		$this->firm->roles()->save(Role::findOrFail('firm'));
		Sanctum::actingAs($this->firm);

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

		$this->skill = Skill::create([
			'title' => 'Laravel'
		]);

		$this->job->skills()->attach($this->skill);
	}

    public function test_detach_job_skill_status()
    {
		$response = $this->put(route('jobs.update', ['job' => $this->job['id']]), [
			'skill' => [
				'id' => $this->skill['id'],
				'attach_or_detach' => false
			]
		]);
        $response->assertStatus(200);
    }

	public function test_detach_job_skill_data()
    {
		$this->put(route('jobs.update', ['job' => $this->job['id']]), [
			'skill' => [
				'id' => $this->skill['id'],
				'attach_or_detach' => false
			]
		]);
        $this->assertDatabaseMissing('job_skill', [
			'job_id' => $this->job['id'], 
			'skill_id' => $this->skill['id'], 
		]);
    }

	/**
     * @dataProvider badDataProvider
     */
	public function test_bad_data(
		$job_id,
		$skill, 
		$expected_result
	)
	{
		$data_to_send = [
			'skill' => [
				'id' => $skill['id'],
				'attach_or_detach' => $skill['attach_or_detach']
			]
		];

		if(isset($job_id)) {
			$data_to_send['id'] = $job_id;
		}

        $response = $this->put(route('jobs.update', ['job' => $this->job['id']]), $data_to_send);

		if(isset($job_id)) {
			$this->assertDatabaseHas('firms_jobs', [
				'id' => $this->job['id'],
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
		} else {
			$response->assertSessionHasErrors($expected_result);
		}
	}

	public function badDataProvider() : array
	{
		return [
			[
				'job_id' => 1,
				'skill' => [
					'id' => 1,
					'attach_or_detach' => false,
				],
				'expected_result' => []
			],
			[
				'job_id' => null,
				'skill' => [
					'id' => 1,
					'attach_or_detach' => 2,
				],
				'expected_result' => ['skill.attach_or_detach']
			]
		];
	}
}
