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
        Schema::create('andon_logs', function (Blueprint $table) {
            $table->id();
            $table->string('andon_type');
            $table->string('andon_line');
            $table->string('andon_station');
            $table->enum('status',['active', 'inactive']);
            $table->string('reason')->nullable();
            $table->string('repairTime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('andon_logs');
    }
};
