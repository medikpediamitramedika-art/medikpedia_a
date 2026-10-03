<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'judul',
        'deskripsi',
        'konten',
        'tipe',
        'file',
        'thumbnail',
        'views',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views' => 'integer',
    ];

    // Scope untuk berita published
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope untuk urutan terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Get tipe badge
    public function getTipeBadge()
    {
        return match($this->tipe) {
            'diskon'        => '🏷️ Diskon',
            'flash_sale'    => '⚡ Flash Sale',
            'bundling'      => '📦 Bundling',
            'promo_spesial' => '🎁 Promo Spesial',
            default         => '🏷️ Promo'
        };
    }

    // Increment views
    public function incrementViews()
    {
        $this->increment('views');
    }
}
