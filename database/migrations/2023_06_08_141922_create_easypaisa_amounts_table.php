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
        Schema::create('easypaisa_amounts', function (Blueprint $table) {
            $table->id();
            $table->string("easypaisa_amount_date");
            $table->integer("invoice_no");
            $table->integer("current_amount");
            $table->integer("add_amount")->default(0);
            $table->integer("deducted_amount")->default(0);
            $table->integer("remaining_amount");
            $table->integer("return_amount")->default(0);
            $table->integer("branch");
            // $table->string("use_for")->default(0);
            // $table->string("which_table")->nullable();
            $table->string("status")->nullable();
            $table->string("remarks")->nullable();
            $table->string("operator")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('easypaisa_amounts');
    }
};
