<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function create(Request $request)
    { 
        $redirect = $request->input('redirect');

        return Inertia::render('Auth/Login', [
            'redirect' => $redirect,
        ]);

        // return Inertia::render('Auth/Login');    
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Get the redirect URL from the query parameter or use a default if not provided
            $redirectUrl = $request->input('redirect', url('/'));
            
            return redirect()->intended($redirectUrl);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function destroy()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function update(Request $request, User $user)
    {
        $data = Request::validate([
            'name' => ['required', 'max:90'],
        ]);
        $user->update($data);


        return Redirect::route('users.index');
    }
}
