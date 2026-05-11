<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('level', [
                'Level 1 (Ketua)',
                'Level 2 (Manajerial)',
                'Level 3 (Staf Ahli)',
                'Level 4 (Pelaksana)'
            ])->default('Level 4 (Pelaksana)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
