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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("employee_no")->nullable();
            $table->string("employee_name");
            $table->string("employee_post")->nullable();
            $table->string("employee_type");
            $table->string("cnic")->nullable();
            $table->string("phone_no")->nullable();
            $table->string("father_cnic")->nullable();
            $table->string("father_phone_no")->nullable();
            $table->string("basic_sallary")->nullable();
            $table->integer("employee_branch");
            $table->string("employee_status");
            $table->string("operator");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
