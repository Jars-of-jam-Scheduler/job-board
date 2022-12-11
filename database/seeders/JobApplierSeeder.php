<?php

namespace Database\Seeders;

use App\Models\{User, Role};

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JobApplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
		->hasAttached([Role::findOrFail('job_applier')])
		->create([
			'name' => 'My Applier',
			'email' => 'applier@applier.fr',
			'password' => Hash::make('azerty'),
		]);
    }
}
