<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $table = "detail_transaksis";
    protected $fillable = ['id_transaksi', 'id_barang', 'jumlah'];
}
