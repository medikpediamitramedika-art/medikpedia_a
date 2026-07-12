<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoProduct extends Model
{
    protected $table = 'promo_products';

    protected $fillable = [
        'judul',
        'subjudul',
        'gambar',
        'url_tujuan',
        'label_tombol',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif'  => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope: hanya promo aktif, urut by urutan
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true)->orderBy('urutan')->orderBy('id');
    }

    /**
     * URL gambar lengkap
     */
    public function getImageUrlAttribute(): string
    {
        return url('storage/' . $this->gambar);
    }
}
