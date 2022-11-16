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

        Schema::create('firms_jobs', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('firm_id');
			$table->foreign('firm_id')->references('id')->on('users');

			$table->string('title')->nullable();
			$table->text('presentation')->nullable();
			$table->integer('min_salary')->nullable();
			$table->integer('max_salary')->nullable();
			$table->enum('working_place', ['full_remote', 'hybrid_remote', 'no_remote'])->nullable();
			$table->enum('working_place_country', ['fr'])->nullable();
			$table->enum('employment_contract_type', ['cdi', 'cdd'])->nullable();
			$table->string('contractual_working_time')->nullable();
			$table->enum('collective_agreement', ['syntec'])->nullable();
			$table->boolean('flexible_hours')->nullable();
			$table->boolean('working_hours_modulation_system')->nullable();
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
		Schema::drop('firms_jobs');
    }
};
