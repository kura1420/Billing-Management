<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    const FOLDER = 'mobile.auth.';

    public function loginPage()
    {
        if (session()->get('mobile_user_login')) {
            return redirect('/m/pages');
        }

        return view(self::FOLDER . 'login');
    }

    public function loginCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);
        } else {
            $user = User::with('user_profiles')->where('email', $request->email)
                ->where('active', 1)
                ->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    session([
                        'mobile_user_login' => TRUE,
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
}
