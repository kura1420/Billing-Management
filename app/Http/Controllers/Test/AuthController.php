<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\Forgot;
use App\Notifications\ResetSuccess;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function forgot()
    {
        $user = User::first();

        return (new Forgot())
            ->toMail($user);
    }
    
    public function reset()
    {
        $user = User::first();

        return (new ResetSuccess())
            ->toMail($user);
    }
}
