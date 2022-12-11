<?php

namespace Database\Seeders;

use App\Models\Job;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Job::factory()
		->count(100)
		->create();
    }
}
