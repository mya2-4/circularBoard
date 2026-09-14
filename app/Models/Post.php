<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'summary',
        'body',
        'status',
        'published_at',
        'read_mode',
        'send_reminder',
        'views_count',
        'created_by',
    ];

    protected $casts = [
        'published_at'  => 'datetime',
        'send_reminder' => 'boolean',
    ];

    // カテゴリの内部値 → 表示ラベル
    protected static array $categoryLabels = [
        'event'       => '地域イベント',
        'notice'      => 'お知らせ',
        'disaster'    => '防災・安全',
        'environment' => '生活環境',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return static::$categoryLabels[$this->category] ?? $this->category;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'post_region');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(PostRead::class);
    }

    // 既読状況の集計（一覧・詳細画面で使用）
    public function getConfirmedCountAttribute(): int
    {
        return $this->reads()->where('status', 'confirmed')->count();
    }

    public function getReadCountAttribute(): int
    {
        return $this->reads()->where('status', 'read')->count();
    }

    public function getUnreadCountAttribute(): int
    {
        return $this->reads()->where('status', 'unread')->count();
    }
}