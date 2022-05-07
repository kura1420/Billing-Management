<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    //
    public function index()
    {
        $id = session()->get('user_data')['id'];
        
        $userProfile = UserProfile::where('user_id', $id)->first();

        return response()->json($userProfile, 200);
    }
    
    public function store(UserProfileRequest $request)
    {
        $id = session()->get('user_data')['id'];

        $user = User::find($id);
        $userProfile = $user->user_profiles()->first();

        $userProfile->update([
            'fullname' => $request->fullname,
            'email' => strtolower($request->email),
            'telp' => $request->telp,
            'handphone' => $request->handphone,
        ]);

        $userField = [
            'name' => $request->fullname,
            'email' => strtolower($request->email),
        ];

        if ($request->password) {
            $userField['password'] = bcrypt($request->password);
        }

        $user->update($userField);

        return response()->json('OK', 200);
    }

    public function logout()
    {
        session()->flush();

        return response()->json('Logout success', 201);
    }
}
