<?php

use App\Http\Controllers\admin\CurrencyController;
use App\Http\Controllers\admin\FinancialController;
use App\Http\Controllers\admin\MembershipLevelController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\MiningPoolController;
use App\Http\Controllers\admin\StatisticsController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\agent\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/login', [AuthController::class, 'login'])->middleware('log.admin:login,登录');

Route::middleware(['auth:sanctum', 'check.token.expiry'])->group(function () {
    Route::get('account', [AuthController::class, 'account']);
});


Route::middleware(['auth:sanctum', 'check.token.expiry'])->group(function () {

    Route::prefix('/admin')->group(function () {
        Route::get('menus', [AuthController::class, 'menus']);
    });
    Route::prefix('/web3')->group(function () {
        Route::prefix('/members')->group(function () {
            Route::get('/list', [UserController::class, 'getMemberList']);
            Route::post('/get_up_agents', [UserController::class, 'getUpAgent']); // 上级代理
            Route::post('/get_down_agents', [UserController::class, 'getDownAgent']); // 下级代理
            Route::get('/show/{id}', [UserController::class, 'show']);
            Route::middleware('check.super.admin')->group(function () {
                Route::post('/deposit', [UserController::class, 'depositAssets'])->middleware('log.admin:edit,充值');
                Route::post('/freeze', [UserController::class, 'freezeUser'])->middleware('log.admin:edit,冻结'); //冻结用户
                Route::post('/set_internal', [UserController::class, 'setInternalAccount'])->middleware('log.admin:edit,设置内部账号'); //设置内部账号
                Route::post('/account/add', [UserController::class, 'addInternalAccount'])->middleware('log.admin:edit,添加虚拟号'); //添加虚拟号
                Route::post('/set/membership_level', [UserController::class, 'setMembershipLevel'])->middleware('log.admin:edit,预设置会员等级'); //预设置会员等级
                Route::post('/change/{state}', [UserController::class, 'changeState'])->middleware('log.admin:edit,兑换功能'); //冻结用户

            });
        });

        Route::prefix('/statistics')->group(
            function () {
                Route::post('/user', [StatisticsController::class, 'userStatistics']);
                Route::post('/recharge', [StatisticsController::class, 'rechargeStatistics']);
                Route::post('/withdraw', [StatisticsController::class, 'withdrawStatistics']);
                Route::post('/all', [StatisticsController::class, 'allStatistics']);
            }
        );

        Route::prefix('/roles')->group(
            function () {
                Route::get('/', [MenuController::class, 'role_list']);
            }
        );
        //币种管理
        Route::prefix('/currencies')->group(
            function () {
                Route::get('/', [CurrencyController::class, 'index']);
            }
        );
        //矿池
        Route::prefix('/mining-pools')->group(
            function () {
                // Route::get('/list', [MiningPoolController::class, 'index']);
                Route::get('/cycle_item/list', [MiningPoolController::class, 'getMiningPoolCycleItemList']);
                Route::get('/order_list', [MiningPoolController::class, 'getOrderList']);
            }
        );
        Route::prefix('/membership_levels')->group(
            function () {
                Route::apiResource('index', MembershipLevelController::class);
            }
        );
        //财务中心
        Route::prefix('/financial')->group(
            function () {
                Route::any('/recharge_list', [FinancialController::class, 'rechargeList']);
                Route::any('/withdrawal_list', [FinancialController::class, 'withdrawalList']);
                //
                Route::any('/user_asset_log', [FinancialController::class, 'UserAssetLogList']);
            }
        );
    });
});
