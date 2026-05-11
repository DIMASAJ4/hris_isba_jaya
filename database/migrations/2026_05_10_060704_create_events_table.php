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
        Schema::create('events', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('event_code')->unique();
            $blueprint->string('title');
            $blueprint->date('event_date');
            $blueprint->string('location');
            $blueprint->text('description');
            $blueprint->text('notes')->nullable();
            $blueprint->enum('status', ['Draft', 'Selesai', 'Dibatalkan'])->default('Draft');
            $blueprint->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
