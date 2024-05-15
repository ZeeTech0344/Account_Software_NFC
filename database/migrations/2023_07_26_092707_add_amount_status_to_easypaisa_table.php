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
        Schema::table('easypaisa_paid_amounts', function (Blueprint $table) {
            $table->string("amount_status")->default("Out");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('easypaisa_paid_amounts', function (Blueprint $table) {
            $table->string("amount_status")->default("Out");
        });
    }
};
