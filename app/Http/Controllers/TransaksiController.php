<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
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

        return response()->json(['message' => $status ]);
    }
}