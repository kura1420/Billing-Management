<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\Forgot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);
        } else {
            $username = $request->username;

            $user = User::with('user_profiles')->where('email', $username)
                ->where('active', 1)
                ->orWhere('username', $username)
                ->where('active', 1)
                ->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    session([
                        'user_login' => TRUE,
                        'user_data' => $user,
                        'user_profile' => $user->user_profiles,
                    ]);

                    $user->update([
                        'last_login' => Carbon::now(),
                    ]);

                    return response()->json($user, 200);
                } else {
                    return response()->json([
                        'data' => [
                            'email' => 'Salah username dan password'
                        ],
                        'status' => 'NOT'
                    ], 422);
                }
            } else {
                return response()->json([
                    'data' => [
                        'email' => 'User tidak ditemukan'
                    ],
                    'status' => 'NOT'
                ], 422);
            }            
        }        
    }

    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_forgot' => 'required|string|email',
        ], [
            'email_forgot' => 'email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ]);
        } else {
            $user = User::where('email', $request->email_forgot)
                ->where('active', 1)
                ->first();

            if ($user) {
                $token = Str::random(30);

                $user->notify(new Forgot($token));

                $user->update([
                    'token' => $token,
                ]);

                return response()->json('OK', 201);
            } else {
                return response()->json([
                    'data' => [
                        'email' => 'User tidak ditemukan'
                    ],
                    'status' => 'NOT'
                ], 422);
            }
        }        
    }
}
