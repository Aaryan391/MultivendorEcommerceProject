<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $user = User::where('google_id', $google_user->getId())->orWhere('email', $google_user->getEmail())->first();

            if ($user) {
                // If user exists, log them in
                Auth::login($user);
                return redirect()->intended('/');
            } else {
                // If user doesn't exist, redirect to registration page with pre-filled data
                session([
                    'google_data' => [
                        'name' => $google_user->getName(),
                        'email' => $google_user->getEmail(),
                        'google_id' => $google_user->getId(),
                    ]
                ]);
                return redirect()->route('register');
            }
        } catch (\Throwable $th) {
            // Handle any errors that occur
            return redirect()->route('login')->with('error', 'Something went wrong with Google login: ' . $th->getMessage());
        }
    }
}
