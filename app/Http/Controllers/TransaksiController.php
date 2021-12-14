<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class TransaksiController extends Controller
{
    public function batalkanPesanan($id_transaksi)
    {
        $transaksi = Transaksi::where('id_transaksi', $id_transaksi)->first();
        $status = "Dibatalkan";

        $transaksi->update([
            'alamat' => $status
        ]);

        return response()->json(['message' => 'Berhasil Membatalkan Pesanan']);
    }
}