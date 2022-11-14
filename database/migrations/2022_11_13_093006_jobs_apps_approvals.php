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
		Schema::create('jobs_apps_approvals', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('job_application_id');
			$table->foreign('job_application_id')->references('id')->on('job_user');

			$table->boolean('accepted_or_refused')->comments('Accepted = 1 ; Refused = 0.');
			$table->string('firm_message');

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
        Schema::dropIfExists('jobs_apps_approvals');
    }
};
