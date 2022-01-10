<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\User;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class TransaksiController extends Controller
{
    public function batalkanPesan($id_transaksi)
    {
        $waktu = Carbon::now()->toDateTimeString();

        $transaksi = Transaksi::where('id', $id_transaksi)->first();
        $status = "Dibatalkan";
        $title = "Pembatalan Pesanan";
        $pesan = "Pesanan Berhasil Dibatalkan";

        $transaksi->update([
            'status_transaksi'=> $status
        ]);

        $notifikasi = Notifikasi::create([
            'id_transaksi' => $transaksi->id,
            'waktu' => $waktu,
            'pesan' => $pesan,
            'title' => $title
        ]);

        return response()->json(['message' => 'Berhasil Membatalkan Pesanan']);
    }

    public function memesan(Request $request)
    {
        $alamat = $request->input('alamat');
        $waktu = Carbon::now()->toDateTimeString();
        $biaya_kirim = $request->input('biaya_kirim');
        $status_transaksi = "Diproses";
        $id_user = $request->input('id_user');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $status_jemput = $request->input('status_jemput');

        $keranjang = Keranjang::where('id_user', $id_user)->select('id_barang', 'jumlah')->get();
        $transaksis = Transaksi::create([
            'waktu' => $waktu,
            'alamat' => $alamat,
            'biaya_kirim' => $biaya_kirim,
            'status_transaksi' => $status_transaksi,
            'id_user' => $id_user,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status_jemput' => $status_jemput
        ]);

        $user = User::find($id_user);

        foreach ($keranjang as $a) {
            $id_barang = $a->id_barang;
            $jumlah = $a->jumlah;

            $dtransaksi = DB::table('detail_transaksis')->insert([
                'id_transaksi' => $transaksi->id,
                'id_barang' => $id_barang,
                'jumlah' => $jumlah
            ]);

            Keranjang::where('id_user', $id_user)->where('id_barang', $id_barang)->delete();
        }

        $transaksi = DB::table('transaksis')->max('id');

        $notip = new stdClass();
        $notip->title = "Pengingat Pesanan";
        $notip->message = "Silahkan Jemput Pesanan Anda";

        $title = "Pengingat Pesanan";
        $message = "Silahkan Jemput Pesanan Anda";

        $notifikasi = Notifikasi::create([
            'id_transaksi' => $transaksi->id,
            'waktu' => $waktu,
            'pesan' => $message,
            'title' => $title
        ]);

        return response()->json(['message' => 'Berhasil Memesan']);
        return view('notifikasi.notifikasi', compact('user', 'notip', 'transaksi'));
    }

    public function notip($id){
        $transaksi = Transaksi::find($id);
        $user = User::find($transaksi->id_user);

        $notip = new stdClass();
        $notip->title = "Pengingat Pesanan";
        $notip->message = "Silahkan Jemput Pesanan Anda";

        return view('notifikasi.notifikasi', compact('user', 'notip', 'transaksi'));
    }
}