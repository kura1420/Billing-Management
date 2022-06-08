<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RouterOS\{Client, Query, Config};
use RouterOS\Exceptions\ConnectException;

class MikrotikController extends Controller
{
    //
    public function index()
    {
        return 123;
    }

    public function checkConnecion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|ip',
            'port' => 'required|numeric',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);            
        } else {
            try {
                $host = $request->host;
                $port = $request->port;
                $username = $request->username;
                $password = $request->password;
    
                $config = (new Config())
                    ->set('host', $host)
                    ->set('port', $port)
                    ->set('user', $username)
                    ->set('pass', $password);
    
                $client = new Client($config);
                $query = new Query('/ip/address/print');
                $res = $client->query($query)->read();
    
                return response()->json('Connected', 200);
            } catch (\Exception $e) {
                $message = $e->getMessage() . PHP_EOL;
    
                return response()->json($message, 500);
            }
        }
    }

    public function users(Request $request)
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
            $query = new Query('/user/print');
            $res = $client->query($query)->read();

            return response()->json($res, 200);
        } catch (\Exception $e) {
            $message = $e->getMessage() . PHP_EOL;

            return response()->json($message, 500);
        }
    }

    public function ipAddress(Request $request)
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
            $query = new Query('/ip/address/print');
            $res = $client->query($query)->read();

            return response()->json($res, 200);
        } catch (\Exception $e) {
            $message = $e->getMessage() . PHP_EOL;

            return response()->json($message, 500);
        }
    }
}
