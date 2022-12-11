<?php

namespace Database\Seeders;

use App\Models\{User, Role};

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FirmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
		->hasAttached([Role::findOrFail('firm')])
		->create([
			'name' => 'My Firm',
			'email' => 'firm@firm.fr',
			'password' => Hash::make('azerty'),
		]);
    }
}
