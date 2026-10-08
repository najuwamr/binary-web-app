<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showTwoFactorChallenge(): View
    {
        return view('auth.two-factor-challenge');
    }
}
