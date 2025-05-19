<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        
        $credentials = $request->only('Login', 'password');

        $user = User::where('Login', $request->Login)->first();
        
        if ($user && $request->password === $user->password) {
            Auth::login($user); 
            $request->session()->regenerate();  
            return redirect()->intended('main');  
        }

        return back()->withErrors([
            'Login' => 'Credenciales inválidas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');  
    }

    public function username()
    {
        return 'Login';
    }

    public function main()
    {
        return view('main');
    }
}