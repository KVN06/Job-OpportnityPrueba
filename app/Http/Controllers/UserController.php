<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Unemployed;

class UserController extends Controller
{
    // Método para mostrar la vista del formulario de registro
    public function create() {
        return view('register');
    }

    // Método para manejar la creación de un nuevo usuario
    public function agg_user(Request $request) {
        // Crear una nueva instancia del modelo User
        $user = new User();
        
        // Asignar los valores recibidos desde el formulario al modelo
        $user->name = $request->name; // Nombre del usuario
        $user->email = $request->email; // Correo electrónico
        $user->password = bcrypt($request->password); // Contraseña encriptada
        $user->type = $request->type; // Tipo de usuario (empresa o desempleado)
        $user->email_verified_at = now(); // Asignar la fecha y hora actual como verificación del correo
        $user->save(); // Guardar el nuevo usuario en la base de datos
    
        // Autenticar al usuario después de crearlo
        Auth::login($user);
        
        // Redirigir a un formulario dependiendo del tipo de usuario
        if ($user->type == 'unemployed') {
            return redirect()->route('unemployed-form'); // Si es desempleado, redirige al formulario de desempleado
        } else {
            return redirect()->route('company-form'); // Si es empresa, redirige al formulario de empresa
        }
    }

    // Método para manejar el inicio de sesión de un usuario
    public function login(Request $request) {
        // Validación de las credenciales de inicio de sesión
        $credentials = [
            "email" => $request->email, // Correo electrónico
            "password" => $request->password // Contraseña
        ];
        
        // Si el usuario ha marcado "recordarme", se establece la opción de recordar
        $remember = $request->remember ? true : false;
        
        // Intentar iniciar sesión con las credenciales proporcionadas
        if (Auth::attempt($credentials, $remember)) {
            // Si el inicio de sesión es exitoso, regenerar la sesión para mayor seguridad
            $request->session()->regenerate();
            // Redirigir al usuario a la página que intentaba acceder
            return redirect()->intended(route('home'));
        } else {
            // Si las credenciales son incorrectas, redirigir a la página de inicio de sesión nuevamente
            return redirect('login');
        }
    }

    // Método para cerrar sesión de un usuario
    public function logout(Request $request) {
        // Cerrar la sesión del usuario autenticado
        Auth::logout();
        
        // Invalidar la sesión actual y regenerar el token de CSRF para seguridad
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirigir al usuario a la página de inicio de sesión
        return redirect(route('login'));
    }
}
