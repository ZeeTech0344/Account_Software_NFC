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
        Schema::create('easypaisa_amount_paid_details', function (Blueprint $table) {
            $table->id();
            $table->string("easypasia_amount_date");
            $table->string("employee_type");
            $table->string("locations_id");
            $table->integer("employee_others");
            $table->string("purpose");
            $table->string("advance_payment_month")->nullable();
            $table->integer("paid_amount");
            $table->string("remarks");
            $table->integer("easypaisa_amount_id");
            $table->boolean("return")->default(false);
            $table->string("operator");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('easypaisa_amount_paid_details');
    }
};
