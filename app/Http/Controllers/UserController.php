<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class UserController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8',
            'no_hp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/'
        ]);
        $username = $request->input('username');
        $no_hp = $request->input('no_hp');
        $password = Hash::make($request->input('password'));
        $foto = "/img/default.png";

        $user = User::create([
            'username' => $username,
            'password' => $password,
            'no_hp' => $no_hp,
            'foto' => $foto
        ]);

        return response()->json(['message' => 'Pendaftaran pengguna berhasil dilaksanakan']);
    }

    public function show($id){
        $listUser = new \stdClass();
        // $data2 = DB::select("SELECT * FROM users where id=$id");
        $data2 = DB::table('users')
                ->where('id', $id)->get();
        $listUser->user = $data2;
        return response()->json($listUser); 
    }

    public function editDataDiri(Request $request, $id){
        $id = DB::table('users')
                ->where('id', $id)->get();
        $tanggal_lahir = $request->input('tanggal_lahir');
        $no_hp = $request->input('no_hp');
        $jenis_kelamin = $request->('jenis_kelamin');
        $user -> update([
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'no_hp' => $no_hp
        ]);
        return response()->json(['message' => 'Berhasil ubah kata sandi']);
    }
}