<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create() {
        return view('register');
    }

    public function agg_user(Request $request) {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->type = $request->type;
        $user->email_verified_at = now(); // Verificación inmediata
        $user->save();
        Auth::login($user); // Iniciar sesión automáticamente después de crear el usuario
        return redirect()->route('home') ;
    }


    public function login(Request $request) {
        $credentials = [
            "email" => $request->email,
            "password" => $request->password
        ];
        
        $remember = $request->remember ? true : false;
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        } else {
            return redirect('login');
        }
    }



    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }

}
