<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('category'); // event / notice / disaster / environment
            $table->string('summary')->nullable();
            $table->text('body');

            $table->enum('status', ['draft', 'public'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // 既読・確認設定
            $table->enum('read_mode', ['read_only', 'confirmation_required'])->default('read_only');
            $table->boolean('send_reminder')->default(false);

            $table->unsignedInteger('views_count')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};