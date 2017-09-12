<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->getClientIp();
        if (file_exists(storage_path('officeIp.txt'))){
            $store_ip = Storage::disk('client')->get('officeIp.txt');
            $whiteList = array_merge(config('api.whiteList.token'),array($store_ip));
        }
        else{
            $whiteList = config('api.whiteList.token');
        }
        if( in_array($ip,$whiteList ) ){
            return $next($request);
        }
        else{
            return response('Unauthorized.', 401);
        }
    }
}
