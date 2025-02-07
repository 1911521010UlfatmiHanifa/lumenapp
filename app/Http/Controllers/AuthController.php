<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use stdClass;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:8'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $no_hp = $request->input('no_hp');
        $fcm_token = $request->input('fcm_token');

        $user = User::where('username', $username)->orWhere('no_hp', $no_hp)->first();
        if (!$user) {
            return response()->json(['message' => 'Login failed'], 401);
        }

        $isValidPassword = Hash::check($password, $user->password);
        if (!$isValidPassword) {
            return response()->json(['message' => 'Login failed'], 401);
        }

        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token' => $generateToken,
            'fcm_token' => $fcm_token
        ]);

        $api = new \stdClass();

        $api->AuthData = $user;
        return response()->json($api);
    }

    public function logout(Request $request){
        $user = \Auth::user();
        $user->token = null;
        $user->fcm_token = null;
        $user->save();

        return response()->json(['message' => 'Pengguna telah logout']);
    }
}
