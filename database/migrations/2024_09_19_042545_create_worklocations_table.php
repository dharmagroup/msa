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
        Schema::create('worklocations', function (Blueprint $table) {
            $table->string('worklocation_id')->primary();
            $table->string('worklocation_code')->unique();
            $table->string('worklocation_name')->unique();
            $table->string('worklocation_address')->nullable();
            $table->string('company_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worklocations');
    }
};
