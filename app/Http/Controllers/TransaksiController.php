<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class TransaksiController extends Controller
{
    public function batalkanPesan($id_transaksi)
    {
        $transaksi = Transaksi::where('id', $id_transaksi)->first();
        $status = "Dibatalkan";

        $transaksi->update([
            'status_transaksi'=> $status
        ]);

        return response()->json(['message' => 'Berhasil Membatalkan Pesanan' ]);
    }

    public function memesan (Request $request)
    {
        $alamat = $request->input('alamat');
        $waktu = Carbon::now()->toDateTimeString();
        $biaya_kirim = $request->input('biaya_kirim');
        $status_transaksi = $request->input('status_transaksi');
        $id_user = $request->input('id_user');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $jumlah= $request->input('jumlah');
        $id_barang = $request->input('id_barang');

        $transaksi = Transaksi::create([
            'waktu' => $waktu, 
            'alamat' => $alamat, 
            'biaya_kirim' => $biaya_kirim, 
            'status_transaksi' => $status_transaksi, 
            'id_user' => $id_user, 
            'latitude' => $latitude, 
            'longitude' => $longitude
        ]);

        $dtransaksi = DetailTransaksi::create([
            'id_transaksi' => $transaksi->id,
            'id_barang' => $id_barang,
            'jumlah' => $jumlah
        ]);

        $keranjang = Keranjang::where('id_barang', $id_barang)->where('id_user', $id_user)->delete();

        return response()->json(['message' => 'Berhasil Memesan' ]);
    }
}