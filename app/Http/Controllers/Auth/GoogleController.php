<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(uniqid()), // random password
                // add other fields if needed
            ]
        );

        Auth::login($user, true);

        return redirect()->intended('/'); // or wherever you want
    }
}
