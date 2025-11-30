<?php
// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('login', $request->login)->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {
            
            // Сохраняем все данные пользователя в сессию
            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_login' => $user->login,
                'user_role' => $user->role_id,
                'user_email' => $user->email,
                'user_phone' => $user->phone,
                'user_address' => $user->address
            ]);

            // Редирект в зависимости от роли
            if ($user->role_id === 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'login' => 'Неверный логин или пароль.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        session()->forget([
            'user_id', 'user_name', 'user_login', 'user_role', 
            'user_email', 'user_phone', 'user_address'
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}