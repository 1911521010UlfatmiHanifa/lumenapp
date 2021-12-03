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

        $user = User::create([
            'username' => $username,
            'password' => $password,
            'no_hp' => $no_hp
        ]);

        return response()->json(['message' => 'Pendaftaran pengguna berhasil dilaksanakan']);
    }

    public function show($id){
        // $listUser = new stdClass();
        $data = DB::select('select * from users where id = ?', $id);
        // $listUser->AuthData = $data;
        return response()->json($data); 
    }
}
