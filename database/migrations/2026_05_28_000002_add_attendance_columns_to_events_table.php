<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('attendance_open')->default(false)->after('status');
            $table->timestamp('attendance_opened_at')->nullable()->after('attendance_open');
            $table->timestamp('attendance_closed_at')->nullable()->after('attendance_opened_at');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['attendance_open', 'attendance_opened_at', 'attendance_closed_at']);
        });
    }
};
