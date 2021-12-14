<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "transaksis";
    protected $fillable = ['waktu', 'alamat', 'biaya_kirim', 'status_transaksi', 'id_user', 'latitude', 'longitude'];
}
