<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/4
 * Time: 13:34
 */

namespace App\Http\Controllers\Frontend;

use Crypt;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfficialClientController extends Controller
{
    public function updateClient(Request $request)
    {
        $client_ip = $request->getClientIp();
        $pwd = md5(($request->client_pwd));
        $key = md5(config('api.updateOfficeIpKey'));
        if ($pwd == $key){
            Storage::disk('client')->put('officeIp.txt', $client_ip);
            return 'ip is store';
        }
        else{
            return "ip store fail";
        }

    }
}