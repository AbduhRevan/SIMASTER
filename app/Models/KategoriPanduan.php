<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriPanduan extends Model
{
    use HasFactory;

    protected $table = 'kategori_panduan';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // Relasi ke Item Panduan (nama method konsisten: items)
    public function items(): HasMany
    {
        return $this->hasMany(ItemPanduan::class, 'kategori_panduan_id');
    }

    // Alias untuk backward compatibility (jika ada kode lama yang pakai itemPanduan)
    public function itemPanduan(): HasMany
    {
        return $this->items();
    }

    // Scope untuk kategori aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    // Helper method: hitung jumlah item aktif
    public function getActiveItemsCountAttribute()
    {
        return $this->items()->where('is_active', true)->count();
    }

    // Helper method: cek apakah kategori punya item
    public function hasItems()
    {
        return $this->items()->exists();
    }
}
