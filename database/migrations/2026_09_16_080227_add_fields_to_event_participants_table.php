<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->unsignedInteger('participant_count')->default(1)->after('user_id');
            $table->text('remarks')->nullable()->after('participant_count');
        });
    }

    public function down(): void
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->dropColumn(['participant_count', 'remarks']);
        });
    }
};