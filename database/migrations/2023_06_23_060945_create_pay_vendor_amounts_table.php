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
        Schema::create('pay_vendor_amounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("employee_id");
            $table->bigInteger("paid_amount");
            $table->bigInteger("account_id");
            $table->string("account_name");
            $table->string("remarks")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_vendor_amounts');
    }
};
