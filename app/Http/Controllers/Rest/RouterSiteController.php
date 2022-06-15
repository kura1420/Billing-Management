<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\RouterSiteRequest;
use App\Models\RouterSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RouterOS\{Client, Query, Config};

class RouterSiteController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = RouterSite::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('site', 'like', "%{$search}%")
                ->orWhere('host', 'like', "%{$search}%")
                ->orWhere('port', 'like', "%{$search}%")
                ->orWhere('user', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(RouterSiteRequest $request)
    {
        RouterSite::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'site' => $request->site,
                'active' => $request->active == 'true' ? 1 : 0,
                'host' => $request->host,
                'port' => $request->port,
                'user' => $request->user,
                'password' => $request->password,
                'desc' => $request->desc,
                'command_trigger_list' => $request->command_trigger_list,
                'command_trigger_comment' => $request->command_trigger_comment,
                'command_trigger_terminated' => $request->command_trigger_terminated,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = RouterSite::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        RouterSite::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = RouterSite::orderBy('site')->get();

        return response()->json($rows);
    }

    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'port' => 'required|numeric',
            'user' => 'required|string',
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
                $user = $request->user;
                $password = $request->password;

                $config = (new Config())
                    ->set('host', $host)
                    ->set('port', (int) $port)
                    ->set('user', $user)
                    ->set('pass', $password);

                $client = new Client($config);

                $query = '/ip/address/print';
                $res = $client->query($query)->read();

                return response()->json($res, 200);
            } catch (\Exception $e) {
                $message = $e->getMessage() . PHP_EOL;
    
                return response()->json([
                    'data' => ['host' => $message],
                    'status' => 'NOT'
                ], 422);
            }
        }        
    }

    public function testCommandList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'port' => 'required|numeric',
            'user' => 'required|string',
            'password' => 'required|string',
            'command' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);            
        } else {
            $host = $request->host;
            $port = $request->port;
            $user = $request->user;
            $password = $request->password;
            $command = $request->command;

            try {
                $config = (new Config())
                    ->set('host', $host)
                    ->set('port', (int) $port)
                    ->set('user', $user)
                    ->set('pass', $password);
    
                $client = new Client($config);
    
                $res = $client->query($command)->read();
    
                return response()->json($res, 200);
            } catch (\Exception $e) {
                $message = $e->getMessage() . PHP_EOL;
    
                return response()->json([
                    'data' => ['host' => $message],
                    'status' => 'NOT'
                ], 422);
            }
        }
    }

    public function testCommandComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'port' => 'required|numeric',
            'user' => 'required|string',
            'password' => 'required|string',
            'command_list' => 'required|string',
            'command' => 'required|string',
            'target' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);            
        } else {
            $host = $request->host;
            $port = $request->port;
            $user = $request->user;
            $password = $request->password;
            $command_list = $request->command_list;
            $command = $request->command;
            $target = $request->target;

            try {
                $config = (new Config())
                    ->set('host', $host)
                    ->set('port', (int) $port)
                    ->set('user', $user)
                    ->set('pass', $password);
    
                $client = new Client($config);

                $query = (new Query($command_list))
                    ->where('address', $target);
                
                $result = $client->query($query)->read();

                if (!empty($result)) {
                    $paramID = $result[0]['.id'];

                    $query = (new Query($command))
                        ->equal('.id', $paramID)
                        ->equal('comment', 'Comment from web app');
                    
                    $res = $client->query($query)->read();
    
                    return response()->json($res, 200);
                } else {
                    return response()->json([
                        'data' => ['host' => "Data tidak ditemukan"],
                        'status' => 'NOT'
                    ], 422);
                }
            } catch (\Exception $e) {
                $message = $e->getMessage() . PHP_EOL;
    
                return response()->json([
                    'data' => ['host' => $message],
                    'status' => 'NOT'
                ], 422);
            }
        }
    }

    public function testCommandDisable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'port' => 'required|numeric',
            'user' => 'required|string',
            'password' => 'required|string',
            'command_list' => 'required|string',
            'command_set' => 'required|string',
            'command' => 'required|string',
            'target' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);            
        } else {
            $host = $request->host;
            $port = $request->port;
            $user = $request->user;
            $password = $request->password;
            $command_list = $request->command_list;
            $command_set = $request->command_set;
            $command = $request->command;
            $target = $request->target;

            try {
                $config = (new Config())
                    ->set('host', $host)
                    ->set('port', (int) $port)
                    ->set('user', $user)
                    ->set('pass', $password);
    
                $client = new Client($config);

                $query = (new Query($command_list))
                    ->where('address', $target);
                
                $result = $client->query($query)->read();

                if (!empty($result)) {
                    $paramID = $result[0]['.id'];

                    $query = (new Query($command_set))
                        ->equal('.id', $paramID)
                        ->equal('comment', 'Comment from web app');
                    
                    $client->query($query);

                    sleep(.1);

                    $query = (new Query($command))
                        ->equal('.id', $paramID);

                    $res = $client->query($query)->read();
    
                    return response()->json($res, 200);
                } else {
                    return response()->json([
                        'data' => ['host' => "Data tidak ditemukan"],
                        'status' => 'NOT'
                    ], 422);
                }
            } catch (\Exception $e) {
                $message = $e->getMessage() . PHP_EOL;
    
                return response()->json([
                    'data' => ['host' => $message],
                    'status' => 'NOT'
                ], 422);
            }
        }
    }
}
