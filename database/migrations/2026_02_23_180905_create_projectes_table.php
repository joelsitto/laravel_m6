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
        Schema::create('projectes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('gestor_id')->constrained('users')->cascadeOnDelete();
            $table->string('nom');
            $table->text('descripcio')->nullable();
            $table->string('codi_projecte')->unique();
            $table->enum('estat', ['PLANIFICACIO', 'EN_CURS', 'PAUSAT', 'FINALITZAT', 'CANCELAT'])->default('PLANIFICACIO');
            $table->date('data_inici')->nullable();
            $table->date('data_fi_prevista')->nullable();
            $table->date('data_fi_real')->nullable();
            $table->unsignedInteger('pressupost_hores_estimades');
            $table->decimal('pressupost_hores_reals', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projectes');
    }
};
