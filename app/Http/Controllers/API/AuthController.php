<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\APIResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use APIResponse;

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Cek Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Kredensial yang Anda masukkan salah.', 401);
        }

        // Hapus token lama (opsional, biar satu user cuma punya 1 session aktif)
        $user->tokens()->delete();

        // Buat Token Baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user'  => $user,
            'token' => $token,
        ], 'Login berhasil!');
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logout berhasil!');
    }
}
