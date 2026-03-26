<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projecte_id')->constrained('projectes')->cascadeOnDelete();
            $table->foreignId('creador_id')->constrained('users')->restrictOnDelete();
            $table->string('codi_ticket')->unique();
            $table->string('titol');
            $table->text('descripcio')->nullable();
            $table->enum('estat', ['NOU', 'ASSIGNAT', 'EN_PROGRES', 'EN_REVISIO', 'TANCAT'])->default('NOU');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

