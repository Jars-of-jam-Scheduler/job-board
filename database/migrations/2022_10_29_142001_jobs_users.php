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
		Schema::create('job_user', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger('job_id');
			$table->unsignedBigInteger('user_id');
			$table->foreign('job_id')->references('id')->on('firms_jobs');
			$table->foreign('user_id')->references('id')->on('users');
			$table->text('message')->nullable();

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
		Schema::drop('job_user');
    }
};
