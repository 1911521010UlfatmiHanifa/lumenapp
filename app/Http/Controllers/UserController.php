<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8',
            'no_hp' => 'required'
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
}
