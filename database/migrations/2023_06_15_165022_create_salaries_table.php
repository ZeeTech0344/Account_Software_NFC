<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("employee_id");
            $table->bigInteger("branch_id");
            $table->bigInteger("basic_salary")->nullable();
            $table->bigInteger("advance")->nullable();
            $table->bigInteger("day_of_work_deduction")->nullable();
            $table->bigInteger("amount");
            $table->string("salary_month");
            $table->string("status")->default("Paid");
            $table->bigInteger("account_id")->nullable();
            $table->string("account_name")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
