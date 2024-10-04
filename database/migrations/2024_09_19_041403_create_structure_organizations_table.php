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
        Schema::create('structure_organizations', function (Blueprint $table) {
            $table->string('so_id')->primary();
            $table->string('so_code')->unique();
            $table->string('so_name');
            $table->enum('so_type',['unit','position']);
            $table->string('company_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_organizations');
    }
};
