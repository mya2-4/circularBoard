<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'last_name' => ['required'],
            'first_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'region' => ['required'],
            'region2' => ['required'],
            'password' => ['required', 'confirmed'],
        ]);

        User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'region' => $request->region,
            'region2' => $request->region2,
            'password' => Hash::make($request->password),
            'role' => 0,
        ]);

        return redirect()->route('login');
    }

    public function login(Request $request){
        $request -> validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password , $user->password)) {
        return back()->withErrors([
            'email'=>'メールアドレスまたはパスワードが正しくありません。',
        ]);
    }

    Auth::login($user);

    if ($user->role === 1) {
        return redirect('/admin');
    }

    return redirect('/home');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');

    }
}