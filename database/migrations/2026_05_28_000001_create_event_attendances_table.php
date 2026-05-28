<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('status')->default('Tidak Hadir'); // Hadir, Tidak Hadir, Izin, Sakit (used string instead of enum to avoid truncation issues on InfinityFree)
            $table->timestamp('checked_in_at')->nullable();
            $table->boolean('is_self_checkin')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();

            // Unique constraint: 1 member only 1 attendance per event
            $table->unique(['event_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendances');
    }
};
