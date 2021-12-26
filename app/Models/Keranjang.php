<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;
    protected $table = "keranjangs";
    protected $fillable = ['jumlah', 'id_user', 'id_barang'];
}
