<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Unemployed;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Método para mostrar la vista del formulario de registro
    public function create() {
        return view('register');
    }

    // Método para manejar la creación de un nuevo usuario
    public function agg_user(Request $request) {
        // Validación de los campos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'type' => ['required', 'in:unemployed,company'],
        ]);

        // Crear y guardar el usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Encriptar la contraseña
        $user->type = $request->type;
        $user->email_verified_at = now();
        $user->save();
    
        // Iniciar sesión automáticamente
        Auth::login($user);
        
        // Redirigir según el tipo de usuario
        if ($user->type == 'unemployed') {
            return redirect()->route('unemployed-form'); // Ruta al formulario de desempleado
        } else {
            return redirect()->route('company-form'); // Ruta al formulario de empresa
        }
    }

    // Método para manejar el inicio de sesión
    public function login(Request $request) {
        $credentials = [
            "email" => $request->email,
            "password" => $request->password
        ];
        
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }
        
        // Redirigir con mensaje de error
        return redirect('login')->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    // Método para cerrar sesión
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }

    // Mostrar y actualizar el perfil del usuario
    public function profile()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validación base para todos los usuarios
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        // Reglas adicionales según el rol
        if ($user->isUnemployed()) {
            $rules['profession'] = 'nullable|string|max:255';
            $rules['experience'] = 'nullable|string';
            $rules['location'] = 'nullable|string|max:255';
        } elseif ($user->isCompany()) {
            $rules['company_name'] = 'nullable|string|max:255';
            $rules['description'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        // Actualizar datos básicos del usuario
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Manejar la carga de la foto de perfil
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        // Actualizar datos específicos según el rol
        if ($user->isUnemployed() && $user->unemployed) {
            $user->unemployed->update([
                'profession' => $validated['profession'] ?? null,
                'experience' => $validated['experience'] ?? null,
                'location' => $validated['location'] ?? null,
            ]);
        } elseif ($user->isCompany() && $user->company) {
            $user->company->update([
                'company_name' => $validated['company_name'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);
        }

        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password_confirmation' => 'required',
        ]);

        $user = Auth::user();

        // Verificar si la contraseña es correcta
        if (!Hash::check($request->password_confirmation, $user->password)) {
            return back()->withErrors([
                'password_confirmation' => 'La contraseña ingresada no es correcta.',
            ])->with('showDeleteModal', true);
        }

        // Eliminar registros relacionados según el tipo de usuario
        if ($user->isUnemployed()) {
            // Eliminar portafolios, postulaciones, favoritos, etc.
            $user->unemployed->portfolios()->delete();
            $user->unemployed->jobApplications()->delete();
            $user->unemployed->favoriteOffers()->delete();
            $user->unemployed->delete();
        } elseif ($user->isCompany()) {
            // Eliminar ofertas de trabajo y otros datos relacionados
            $user->company->jobOffers()->delete();
            $user->company->delete();
        }

        // Eliminar mensajes, notificaciones y otros datos comunes
        $user->sentMessages()->delete();
        $user->receivedMessages()->delete();
        $user->notifications()->delete();

        // Cerrar sesión y eliminar el usuario
        Auth::logout();
        $user->delete();

        return redirect()->route('login')->with('success', 'Tu cuenta ha sido eliminada permanentemente.');
    }
}
