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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->integer("employee_id");
            $table->integer("branch_id");
            $table->integer("shift")->nullable();
            $table->integer("rides");
            $table->string("amount");
            $table->string("status");
            $table->string("paid_date")->nullable();
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
        Schema::dropIfExists('rides');
    }
};
