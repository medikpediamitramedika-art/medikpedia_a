<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    protected $fillable = [
        'buyer_type',
        'buyer_name',
        'phone',
        'address',
        'kecamatan',
        'kota',
        'sia',
        'sipa',
        'no_izin_pbf',
        'apj',
        'sika',
        'items',
        'total',
        'original_total',
        'discounted_total',
        'approval_status',
        'payment_method',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'integer',
        'original_total' => 'integer',
        'discounted_total' => 'integer',
    ];

    protected $appends = ['effective_total', 'approval_label'];

    public function getEffectiveTotalAttribute(): int
    {
        $discounted = (int) ($this->attributes['discounted_total'] ?? 0);
        $original = (int) ($this->attributes['original_total'] ?? 0);
        $storedTotal = (int) ($this->attributes['total'] ?? 0);

        if (($this->approval_status ?? 'pending') === 'approved') {
            return $discounted > 0 ? $discounted : ($storedTotal > 0 ? $storedTotal : $original);
        }

        return $original > 0 ? $original : $storedTotal;
    }

    public function getApprovalLabelAttribute(): string
    {
        return match ($this->approval_status) {
            'approved' => 'Setuju',
            'rejected' => 'Tidak',
            default => 'Belum diproses',
        };
    }
}
