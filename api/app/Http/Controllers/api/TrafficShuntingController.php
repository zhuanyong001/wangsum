<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MiningPool;


class TrafficShuntingController extends Controller
{
    //
    public function getKefu(Request $request)
    {

        //重定向到 客服系统
        return ['redirectUrl' => 'http://www.baidu.com'];
    }
}
