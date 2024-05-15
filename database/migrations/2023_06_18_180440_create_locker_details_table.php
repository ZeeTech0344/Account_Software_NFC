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
        Schema::create('locker_details', function (Blueprint $table) {
            $table->id("id");
            // $table->string("paid_date");
            $table->integer("employee_id");
            $table->string("purpose");
            $table->string("paid_for_month_date")->nullable();
            $table->string("status");
            $table->bigInteger("amount");
            $table->string("remarks")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_details');
    }
};

