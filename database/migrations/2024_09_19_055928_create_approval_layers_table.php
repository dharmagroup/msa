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
        Schema::create('approval_layers', function (Blueprint $table) {
            $table->string('layer_id')->primary();
            $table->string('approval_employee_number');
            $table->enum('approval_level',[1,2,3,4,5,6,7,8,9]);
            $table->string('requester_employee_number');
            $table->string('module_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_layers');
    }
};
