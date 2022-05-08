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
        
        $userProfile = UserProfile::with('departements')->where('user_id', $id)->first();

        $result = [
            'total' => 1,
            'rows' => [
                [
                    'name' => 'Fullname',
                    'value' => $userProfile->fullname,
                    'group' => 'Profile',
                    'editor' => 'textbox'
                ],
                [
                    'name' => 'Email',
                    'value' => $userProfile->email,
                    'group' => 'Profile',
                    'editor' => [
                        'type' => 'textbox',
                        'options' => [
                            'validType' => 'email',
                        ],
                    ],
                ],
                [
                    'name' => 'Telp',
                    'value' => $userProfile->telp,
                    'group' => 'Profile',
                    'editor' => 'numberbox'
                ],
                [
                    'name' => 'Handphone',
                    'value' => $userProfile->handphone,
                    'group' => 'Profile',
                    'editor' => 'numberbox'
                ],
                [
                    'name' => 'Departement',
                    'value' => $userProfile->departements->name,
                    'group' => 'Profile',
                ],
                [
                    'name' => 'Password',
                    'value' => '',
                    'group' => 'Profile',
                    'editor' => [
                        'type' => 'passwordbox',
                        'options' => [
                            'showEye' => true
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($result, 200);
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
