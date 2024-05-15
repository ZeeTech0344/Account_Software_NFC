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
        Schema::create('return_amounts', function (Blueprint $table) {
            $table->id();
            $table->integer("employees_id");
            $table->integer("from_easypaisa_accounts_id");
            $table->integer("to_easypaisa_accounts_id");
            $table->integer("previous_amount");
            $table->integer("return_amount");
            $table->string("after_deduction")->nullable( );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_amounts');
    }
};
