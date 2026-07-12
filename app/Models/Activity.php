<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = ['judul', 'deskripsi', 'foto', 'tanggal', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
        'tanggal'      => 'date',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
