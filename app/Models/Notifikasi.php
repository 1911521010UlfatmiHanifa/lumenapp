<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = "notifikasis";
    protected $fillable = ['id_transaksi', 'waktu', 'pesan'];
}
