<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori punya banyak produk
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}