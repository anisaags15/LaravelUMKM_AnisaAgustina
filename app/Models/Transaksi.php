<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'user_id',
        'tanggal',
        'total_harga',
        'nama_pemesan',
        'alamat',
        'no_hp',
        'payment_method',
        'shipping_method',
        'notes',
        'payment_proof',
        'payment_status',
        'confirmed_by',
        'confirmed_at',
         'is_verified_notified',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}