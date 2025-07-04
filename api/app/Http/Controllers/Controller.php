<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    public function success($data = [], $code = 200, $msg = 'success')
    {
        return response()->json(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }
    public function fail($msg = 'fail', $code = -1, $data = [])
    {
        return response()->json(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }
    public function error($msg = 'error', $code = -1, $data = [])
    {
        return response()->json(['code' => $code, 'msg' => $msg, 'data' => $data], 400);
    }
}
