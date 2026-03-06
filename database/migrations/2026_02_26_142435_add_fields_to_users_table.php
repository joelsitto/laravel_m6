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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['GESTOR', 'DESENVOLUPADOR', 'CLIENT', 'ADMIN'])->default('DESENVOLUPADOR')->after('email');
            $table->decimal('tarifa_hora', 8, 2)->nullable()->after('rol');
            $table->foreignId('client_id')->nullable()->after('tarifa_hora')->constrained('clients')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['rol', 'tarifa_hora', 'client_id']);
        });
    }
};
