<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\api\AppController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ConfigController;
use App\Http\Controllers\api\LoanPoolController;
use App\Http\Controllers\api\MiningPoolController;
use App\Http\Controllers\api\TrafficShuntingController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\WalletController;
use App\Models\Role;
use App\Services\QqwryServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/version', [AppController::class, 'getVersion']);
Route::get('/get_app_url', [AppController::class, 'getAppUrl']);

Route::post('/check', [AuthController::class, 'login']);

Route::any('/sendTestMail', [AppController::class, 'sendTestMail']);

Route::prefix('config')->group((function () {
    Route::get('get_lang_msg/{key}', [ConfigController::class, 'getLangMsg']);
    Route::get('get_lang_list', [ConfigController::class, 'getLangList']);
    Route::post('get_configs', [ConfigController::class, 'getConfigs']);
}));

Route::get('/test_ip', function (Request $request) {
    $server = new QqwryServer();
    $ip = $server->q("8.8.8.8");
    //var_dump($ip);
    return $ip;
});


Route::post('/dump_log', function (Request $request) {
    $data = $request->all();
    info(json_encode($data));
    return;
});





Route::middleware('auth:sanctum')->post('/user', [AuthController::class, 'userInfo']);



Route::view('getcs', 'shunt');
Route::post('getKefu', [TrafficShuntingController::class, 'getKefu']);


Route::post('/web3/login', [UserController::class, 'web3Login'])->middleware('redis.lock:address');
//web3 分组

Route::prefix('web3')->group(function () {


    Route::get('/notices', [UserController::class, 'getNotices']);
    Route::get('/currency_list', [WalletController::class, 'currencyList']);
    Route::prefix('mining_pool')->group(function () {
        Route::get('/list', [MiningPoolController::class, 'getMiningPoolList']);
        Route::get('/stat', [MiningPoolController::class, 'miningPoolStatistics']);
    });

    Route::prefix('deposit_pool')->group(function () {
        Route::get('/list/{cate}', [MiningPoolController::class, 'getMiningPoolList']);
    });
    Route::prefix('loan_pool')->group(function () {
        Route::get('/list', [LoanPoolController::class, 'getPoolList']);
    });

    Route::get('/get_membership_list', [WalletController::class, 'getMembershipList']);
    Route::any('/pay/{scene}', [WalletController::class, 'notifyTest']);
});


Route::prefix('web3')->middleware('auth:sanctum')->group(function () {
    Route::any('/user_info', [UserController::class, 'getUserInfo']);

    Route::post('/recharge', [WalletController::class, 'recharge'])->middleware('redis.lock:order_id');
    Route::post('/withdraw', [WalletController::class, 'withdraw']);
    Route::get('/withdraw_orders', [WalletController::class, 'withdrawOrders']);

    Route::post('/exchange', [WalletController::class, 'exchange']);

    Route::get('/get_deposit_address', [WalletController::class, 'depositAddress']);

    Route::any('/get_team', [UserController::class, 'getMyTeam']);
    Route::get('/get_team_stat', [UserController::class, 'getTeamStatistics']);
    Route::post('/get_team_award_logs', [UserController::class, 'getPoolsRebateLogs']);
    Route::post('/get_node_member_stat', [UserController::class, 'getNodeMemberStat']);
    Route::post('/get_asset_logs', [UserController::class, 'getAssetLogs']);


    //质押矿池
    Route::prefix('mining_pool')->group(function () {
        //Route::get('/list', [MiningPoolController::class, 'getMiningPoolList']);
        Route::post('/create_order', [MiningPoolController::class, 'createOrder']);
        Route::get('/order_list', [MiningPoolController::class, 'getOrderList']);
        Route::get('/order_list/{cate}', [MiningPoolController::class, 'getOrderList']);
        Route::get('/order_detail/{id}', [MiningPoolController::class, 'getOrderDetail']);
        Route::post('/withdraw', [MiningPoolController::class, 'withdraw'])->middleware('redis.lock:id');
    });

    //存款池
    Route::prefix('deposit_pool')->group(function () {
        //Route::get('/list', [MiningPoolController::class, 'getMiningPoolList']);
        Route::post('/create_order', [MiningPoolController::class, 'createOrder']);
        Route::get('/order_list/{cate}', [MiningPoolController::class, 'getOrderList']);
        Route::get('/order_detail/{id}', [MiningPoolController::class, 'getOrderDetail']);
        Route::get('/get_deposit_pool_stat', [MiningPoolController::class, 'getDepositPoolStat']);
    });

    //借款模块
    Route::prefix('loan_pool')->group(function () {
        Route::post('/create_order', [LoanPoolController::class, 'createOrder']);
        Route::post('/preview_order', [LoanPoolController::class, 'previewOrder']);

        Route::get('/order_list', [LoanPoolController::class, 'getOrderList']);
        Route::get('/order_detail/{id}', [LoanPoolController::class, 'getOrderDetail']);
        Route::post('/settlement', [LoanPoolController::class, 'settlement'])->middleware('redis.lock:id');
        Route::get('/get_loan_pool_stat', [LoanPoolController::class, 'getLoanPoolStat']);
    });

    //
    Route::get('/all_pool/order_list', [MiningPoolController::class, 'getAllPoolOrderList']);



    Route::prefix('time')->group(function () {
        Route::get('/test', function () {
            return date('Y-m-d H:i:s');
        });
    });
});
