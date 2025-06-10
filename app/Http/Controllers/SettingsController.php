<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar la página principal de configuraciones
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Mostrar configuraciones de perfil
     */
    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    /**
     * Actualizar información del perfil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->only(['name', 'email', 'phone']);

        // Manejar la subida del avatar
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('settings.profile')
                        ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Mostrar configuraciones de seguridad
     */
    public function security()
    {
        return view('settings.security');
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings.security')
                        ->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Mostrar configuraciones de notificaciones
     */
    public function notifications()
    {
        $user = Auth::user();
        $preferences = $user->getPreferences();
        
        return view('settings.notifications', compact('user', 'preferences'));
    }

    /**
     * Actualizar configuraciones de notificaciones
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $preferences = $user->getPreferences();

        $request->validate([
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
            'job_alerts' => ['boolean'],
            'message_notifications' => ['boolean'],
            'training_notifications' => ['boolean'],
            'application_notifications' => ['boolean'],
        ]);

        $preferences->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'push_notifications' => $request->boolean('push_notifications'),
            'job_alerts' => $request->boolean('job_alerts'),
            'message_notifications' => $request->boolean('message_notifications'),
            'training_notifications' => $request->boolean('training_notifications'),
            'application_notifications' => $request->boolean('application_notifications'),
        ]);

        return redirect()->route('settings.notifications')
                        ->with('success', 'Configuraciones de notificaciones actualizadas.');
    }

    /**
     * Mostrar configuraciones de privacidad
     */
    public function privacy()
    {
        $user = Auth::user();
        $preferences = $user->getPreferences();
        
        return view('settings.privacy', compact('user', 'preferences'));
    }

    /**
     * Actualizar configuraciones de privacidad
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();
        $preferences = $user->getPreferences();

        $request->validate([
            'public_profile' => ['boolean'],
            'show_email' => ['boolean'],
            'show_phone' => ['boolean'],
            'allow_messages' => ['boolean'],
            'show_activity' => ['boolean'],
        ]);

        $preferences->update([
            'public_profile' => $request->boolean('public_profile'),
            'show_email' => $request->boolean('show_email'),
            'show_phone' => $request->boolean('show_phone'),
            'allow_messages' => $request->boolean('allow_messages'),
            'show_activity' => $request->boolean('show_activity'),
        ]);

        return redirect()->route('settings.privacy')
                        ->with('success', 'Configuraciones de privacidad actualizadas.');
    }

    /**
     * Mostrar configuraciones de cuenta
     */
    public function account()
    {
        $user = Auth::user();
        return view('settings.account', compact('user'));
    }

    /**
     * Eliminar cuenta
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required'],
            'confirmation' => ['required', 'in:ELIMINAR'],
        ]);

        $user = Auth::user();

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'La contraseña es incorrecta.']);
        }

        // Cerrar sesión
        Auth::logout();

        // Eliminar usuario (esto también eliminará datos relacionados por las foreign keys)
        $user->delete();

        return redirect()->route('login')
                        ->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }
}