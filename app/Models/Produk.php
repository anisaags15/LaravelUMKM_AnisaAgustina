<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks'; 

protected $fillable = [
    'nama', 
    'harga', 
    'stok', 
    'kategori_id', 
    'deskripsi', 
    'foto',
    'is_featured'  
];
    public function kategori()
    {
        // REVISI: Foreign key di tabel ini adalah kategori_id
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}