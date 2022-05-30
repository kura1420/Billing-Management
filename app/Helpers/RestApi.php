<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class RestApi {
    
    protected $API_HOST;
    protected $API_CONFIG;

    public function __construct()
    {
        $appMode = config('app.env');

        if ($appMode == 'local') {
            $api = (object) config('api.dev');
        } else {
            $api = (object) config('api.prod');
        }       

        $this->API_HOST = $api->host;

        $this->API_CONFIG = Http::withOptions([
            'debug' => FALSE,
        ])
        ->withHeaders([
           $api->user_label => $api->user_value,
           $api->pass_label => $api->pass_value,
        ])
        ->acceptJson();
    }

    public static function run($endpoint, $method, $params)
    {
        $class = new RestApi();

        switch ($method) {
            case 'get':
            case 'GET':
                return $class->_get($endpoint, $params);
                break;

            case 'post':
            case 'POST':
                return $class->_post($endpoint, $params);
                break;

            case 'put':
            case 'PUT':
                return $class->_put($endpoint, $params);
                break;
            
            default:
                return "method not defined";
                break;
        }
    }

    protected function _get($endpoint, $params = NULL)
    {
        if (!empty($params)) {
            $target = $this->API_HOST . $endpoint;

            $res = $this->API_CONFIG
                ->get($target, $params);
        } else {
            $target = $this->API_HOST . $endpoint;

            $res = $this->API_CONFIG
                ->get($target);
        }

        $res->throw();
        
        return $res->object();
    }

    protected function _post($endpoint, $params)
    {
        $target = $this->API_HOST . $endpoint;

        $res = $this->API_CONFIG
            ->post($target, $params);

        $res->throw();
        
        return $res->object();
    }

    protected function _put($endpoint, $params)
    {
        $target = $this->API_HOST . $endpoint;

        $res = $this->API_CONFIG
            ->put($target, $params);

        $res->throw();
        
        return $res->object();        
    }

}