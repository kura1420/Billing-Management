<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RouterOS\{Client, Query, Config};
use RouterOS\Exceptions\ConnectException;
use Illuminate\Support\Str;

class HotspotController extends Controller
{
    //
    public function userList(Request $request)
    {
        $host = $request->header('gw');
        $port = (int) $request->header('pr');
        $username = $request->header('us');
        $password = $request->header('ps');

        try {
            $config = (new Config())
                ->set('host', $host)
                ->set('port', $port)
                ->set('user', $username)
                ->set('pass', $password);

            $client = new Client($config);
            $query = new Query('/ip/hotspot/user/print');
            $res = $client->query($query)->read();

            return response()->json($res, 200);
        } catch (\Exception $e) {
            $message = $e->getMessage() . PHP_EOL;

            return response()->json($message, 500);
        }
    }

    public function userStore(Request $request)
    {
        # code...
    }
    
    public function userGenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric',
            'profile' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);
        } else {
            $host = $request->header('gw');
            $port = (int) $request->header('pr');
            $username = $request->header('us');
            $password = $request->header('ps');

            $quantity = $request->quantity;
            $profile = $request->profile;

            try {
                $config = (new Config())
                    ->set('host', $host)
                    ->set('port', $port)
                    ->set('user', $username)
                    ->set('pass', $password);

                $client = new Client($config);

                for ($i=1; $i <= $quantity; $i++) { 
                    $query = (new Query('/ip/hotspot/user/add'))
                        ->equal('server', 'hotspot1')
                        ->equal('name', Str::random(6))
                        ->equal('password', Str::random(6))
                        ->equal('profile', $profile);

                    $client->query($query);

                    sleep(.1);
                }

                return response()->json('OK', 201);
            } catch (\Exception $e) {
                $message = $e->getMessage() . PHP_EOL;

                return response()->json($message, 500);
            }
        }
    }

    public function profileList(Request $request)
    {
        $host = $request->header('gw');
        $port = (int) $request->header('pr');
        $username = $request->header('us');
        $password = $request->header('ps');

        try {
            $config = (new Config())
                ->set('host', $host)
                ->set('port', $port)
                ->set('user', $username)
                ->set('pass', $password);

            $client = new Client($config);
            $query = new Query('/ip/hotspot/profile/print');
            $res = $client->query($query)->read();

            return response()->json($res, 200);
        } catch (\Exception $e) {
            $message = $e->getMessage() . PHP_EOL;

            return response()->json($message, 500);
        }
    }

    public function active(Request $request)
    {
        $host = $request->header('gw');
        $port = (int) $request->header('pr');
        $username = $request->header('us');
        $password = $request->header('ps');

        try {
            $config = (new Config())
                ->set('host', $host)
                ->set('port', $port)
                ->set('user', $username)
                ->set('pass', $password);

            $client = new Client($config);
            $query = new Query('/ip/hotspot/active/print');
            $res = $client->query($query)->read();

            return response()->json($res, 200);
        } catch (\Exception $e) {
            $message = $e->getMessage() . PHP_EOL;

            return response()->json($message, 500);
        }
    }
}
