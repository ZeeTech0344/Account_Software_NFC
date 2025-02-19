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


        Schema::table('pendings', function (Blueprint $table) {
            $table->bigInteger('account_id')->nullable();
            $table->string('account_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendings', function (Blueprint $table) {
            $table->bigInteger('account_id');
            $table->string('account_name');
        });
    }
};
