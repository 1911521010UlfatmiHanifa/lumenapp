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
            'username' => 'required',
            'password' => 'required|min:8'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $FCMToken = $request->input('FCMToken');

        $user = User::where('username', $username)->first();
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
            'FCMToken' => $FCMToken
        ]);

        $api = new \stdClass();

        $api->AuthData = $user;
        return response()->json($api);
    }

    public function logout(Request $request){
        $user = \Auth::user();
        $user->token = null;
        $user->FCMToken = null;
        $user->save();

        return response()->json(['message' => 'Pengguna telah logout']);
    }
}
