<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title');
            $table->string('summary')->nullable()->after('category');
            $table->enum('status', ['draft', 'public'])->default('draft')->after('body');
            $table->timestamp('published_at')->nullable()->after('status');
            $table->enum('read_mode', ['read_only', 'confirmation_required'])->default('read_only')->after('published_at');
            $table->boolean('send_reminder')->default(false)->after('read_mode');
            $table->unsignedInteger('views_count')->default(0)->after('send_reminder');
            $table->foreignId('created_by')->nullable()->after('views_count')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn([
                'category',
                'summary',
                'status',
                'published_at',
                'read_mode',
                'send_reminder',
                'views_count',
            ]);
        });
    }
};