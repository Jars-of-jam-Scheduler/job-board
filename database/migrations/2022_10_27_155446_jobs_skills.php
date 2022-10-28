<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_skill', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger('job_id');
			$table->unsignedBigInteger('skill_id');
			$table->foreign('job_id')->references('id')->on('jobs');
			$table->foreign('skill_id')->references('id')->on('skills');

			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('job_skill');

    }
};
