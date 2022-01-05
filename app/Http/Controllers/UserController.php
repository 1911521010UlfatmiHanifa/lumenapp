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
        $data2 = DB::table('users')
                ->where('id', $id)->get();
        $listUser->user = $data2;
        return response()->json($listUser); 
    }

    public function editDataDiri(Request $request, $id){
        $this->validate($request, [
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'foto' => 'required'
        ]);
        $user = User::where('id', $id)->first();
        $tanggal_lahir = $request->input('tanggal_lahir');
        $no_hp = $request->input('no_hp');
        $jenis_kelamin = $request->input('jenis_kelamin');
        $foto = $request->input('foto');
        $user->update([
            'jenis_kelamin' => $jenis_kelamin,
            'tanggal_lahir' => $tanggal_lahir,
            'no_hp' => $no_hp,
            'foto' => $foto
        ]);
        return response()->json(['message' => 'Berhasil edit data diri']);
    }

    public function ubahSandi(Request $request, $id){
        $this->validate($request, [
            'passwordLama' => 'required',
            'passwordBaru' => 'required'
        ]);
        $user = User::where('id', $id)->first();
        $passwordLama = $request->input('passwordLama');
        if(Hash::check($passwordLama, $user->password)){
            $passwordBaru = Hash::make($request->input('passwordBaru'));
            $user->update([
                'password' => $passwordBaru
            ]);
            return response()->json(['message' => 'Berhasil ubah kata sandi']);
        }else{
            return response()->json(['message' => 'Kata sandi lama tidak sesuai'], 401);
        }
    }
}