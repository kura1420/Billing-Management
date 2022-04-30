<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = User::join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->join('departements', 'user_profiles.departement_id', '=', 'departements.id')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.active',
                    'user_profiles.telp',
                    'user_profiles.handphone',
                        'departements.name as departement_id'
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('users.name', 'like', "%{$search}%")
                ->orWhere('users.email', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(UserRequest $request)
    {
        $field = [
            'name' => $request->name,
            'email' => strtolower($request->email),
            'email_verified_at' => NULL,
            'active' => $request->active == 'true' ? 1 : 0,
            'last_login' => NULL,
            'token' => NULL,
            'remember_token' => NULL,
        ];

        if ($request->password) {
            $field['password'] = bcrypt($request->password);
        }

        $user = User::updateOrCreate(
            [
                'id' => $request->id,
            ],
            $field
        );
        
        UserProfile::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'fullname' => $request->name,
                'email' => strtolower($request->email),
                // 'telp' => $request->telp,
                // 'handphone' => $request->handphone,
                'departement_id' => $request->departement_id,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = User::find($id);
        $user_profile = $row->user_profiles()->first();

        $row->telp = $user_profile->telp;
        $row->handphone = $user_profile->handphone;
        $row->departement_id = \App\Models\Departement::find($user_profile->departement_id);

        return $row;
    }
    
    public function destroy($id)
    {
        User::find($id)->delete();

        return response()->json('OK', 200);
    }
}
