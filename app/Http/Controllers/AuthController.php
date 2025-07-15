<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = Utilisateur::where('email', $credentials['email'])->first();
    if ($user && Hash::check($credentials['password'], $user->password)) {
        Auth::login($user);
        return redirect()->route('base');
    }
    return back()->withErrors(['email' => 'Identifiants invalides'])->withInput();
}

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateur,email',
            'password' => 'required|string|min:6|confirmed',
            'telephone' => 'nullable|string|max:20',
            'type_utilisateur' => 'required|in:client,commercant,livreur,prestataire,admin',
            'rue' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:20',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = Utilisateur::create($data);
        // Création de l'adresse liée
        \App\Models\Addresse::create([
            'rue' => $data['rue'],
            'ville' => $data['ville'],
            'code_postal' => $data['code_postal'],
        ]);
        Auth::login($user);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 