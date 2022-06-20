<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ResetSuccess;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    const FOLDER = 'pages.auth.';

    public function reset($token)
    {
        $login_check = session()->has('user_login');

        if ($login_check) {
            return redirect('/');
        }

        $user = User::where('token', $token)->firstOrFail();

        $newPassword = uniqid();

        $user->notify(new ResetSuccess($newPassword));

        $user->update([
            'password' => bcrypt($newPassword),
            'token' => NULL,
        ]);        
        
        return view(self::FOLDER . 'reset');
    }
}
