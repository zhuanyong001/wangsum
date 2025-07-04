<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //返回404 
    return response()->json(['code' => 404, 'msg' => 'Not Found'], 404);
});

Route::get('/getip', function () {
    return request()->ip();
});

Route::get('/login', function () {
    return ['code' => -10086, 'msg' => '请先登录'];
})->name('login');
