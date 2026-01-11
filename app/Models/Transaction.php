<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'tanggal',
        'keterangan',
        'tipe',
        'jumlah',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function getFormattedTanggalAttribute(): string
    {
        return $this->tanggal->translatedFormat('d M Y');
    }

    public function getFormattedJumlahAttribute(): string
    {
        return number_format($this->jumlah, 0, ',', '.');
    }
}
