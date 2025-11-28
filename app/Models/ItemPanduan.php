<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPanduan extends Model
{
    protected $table = 'item_panduan';

    protected $fillable = [
        'kategori_panduan_id',
        'judul',
        'konten',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Kategori Panduan
    public function kategoriPanduan(): BelongsTo
    {
        return $this->belongsTo(KategoriPanduan::class, 'kategori_panduan_id');
    }

    // Scope untuk item aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
