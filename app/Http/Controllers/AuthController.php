<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ResetSuccess;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    const FOLDER = 'auth.';

    public function login()
    {
        # code...
    }

    public function reset($token)
    {
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
