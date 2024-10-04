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
        Schema::create('andon_lines', function (Blueprint $table) {
            $table->id();
            $table->string('andon_line_name')->unique();
            $table->integer('andon_type_id');
            $table->string('andon_audio_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('andon_lines');
    }
};
