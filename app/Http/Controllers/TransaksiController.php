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

    public function memesan(Request $request)
    {
        $alamat = $request->input('alamat');
        $waktu = Carbon::now()->toDateTimeString();
        $biaya_kirim = $request->input('biaya_kirim');
        $status_transaksi = $request->input('status_transaksi');
        $id_user = $request->input('id_user');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');


        $keranjang = Keranjang::where('id_user', $id_user)->select('id_barang', 'jumlah')->get();

        $transaksi = Transaksi::create([
            'waktu' => $waktu,
            'alamat' => $alamat,
            'biaya_kirim' => $biaya_kirim,
            'status_transaksi' => $status_transaksi,
            'id_user' => $id_user,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        foreach ($keranjang as $a) {
            $id_barang = $a->id_barang;
            $jumlah = $a->jumlah;


            // $dtransaksi = DetailTransaksi::create([
            //     'id_transaksi' => $transaksi->id,
            //     'id_barang' => $id_barang,
            //     'jumlah' => $jumlah
            // ]);

            $dtransaksi = DB::table('detail_transaksis')->insert([
                'id_transaksi' => $transaksi->id,
                'id_barang' => $id_barang,
                'jumlah' => $jumlah
            ]);

            Keranjang::where('id_user', $id_user)->where('id_barang', $id_barang)->delete();
        }

        return response()->json(['message' => 'Berhasil Memesan']);
    }
}