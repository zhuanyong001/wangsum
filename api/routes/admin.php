<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AirDropController;
use App\Http\Controllers\admin\MiningPoolController;
use App\Http\Controllers\admin\CurrencyController;
use App\Http\Controllers\admin\FinancialController;
use App\Http\Controllers\admin\I18nController;
use App\Http\Controllers\admin\LoanPoolController;
use App\Http\Controllers\admin\MembershipLevelController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\NoticeController;
use App\Http\Controllers\admin\StatisticsController;
use App\Http\Controllers\admin\SystemConfigController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\WithdrawalOrderController;
use App\Http\Controllers\ImageUploadController;
use Illuminate\Http\Request;
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
    return '112323';
});



Route::post('/admin/login', [AdminController::class, 'login'])->middleware('log.admin:login,登录');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::middleware('auth:sanctum')->prefix('/api')->group(function () {
    Route::post('/user/list', [AdminController::class, 'getUserList']);
    Route::post('/config/save', [AdminController::class, 'saveConfig']);
    Route::get('/config/get', [AdminController::class, 'getConfig']);
});

Route::middleware(['auth:sanctum', 'check.token.expiry'])->group(function () {
    Route::post('upload', [ImageUploadController::class, 'uploadImage']);
    Route::prefix('/admin')->group(function () {
        Route::get('menus', [AdminController::class, 'menus']);
    });

    Route::post('/home/new_message', [StatisticsController::class, 'getNewMessage']);


    //国际化管理
    Route::prefix('/i18n')->group(
        function () {
            //语言配置
            Route::any('/list', [I18nController::class, 'getList']);
            Route::post('/save', [I18nController::class, 'save']); // 新增
            // Route::post('/save/{id}', [I18nController::class, 'saveLang']); // 修改
            Route::delete('/{id}', [I18nController::class, 'delete']);
            //  Route::post('/setshow', [I18nController::class, 'setshow']);
            //语言管理
            Route::any('/langlist', [I18nController::class, 'getLangList']);
            Route::post('/langsave', [I18nController::class, 'saveLang']); // 新增
            Route::post('/addlang', [I18nController::class, 'importJson']); // 修改
            Route::post('/import_json', [I18nController::class, 'importJson']); // 修改


        }
    );
    Route::prefix('/web3')->group(function () {
        //矿池
        Route::prefix('/mining-pools')->group(
            function () {
                Route::get('/order_list', [MiningPoolController::class, 'getOrderList']);
                Route::any('/order_award_list', [MiningPoolController::class, 'getOrderAwardList']);
                Route::get('/cycle_item/list', [MiningPoolController::class, 'getMiningPoolCycleItemList']);
                Route::middleware('check.super.admin')->group(function () {
                    Route::get('/list', [MiningPoolController::class, 'index']);
                    Route::post('/save', [MiningPoolController::class, 'save'])->middleware('log.admin:edit,新增矿池'); // 新增
                    Route::post('/save/{id}', [MiningPoolController::class, 'save'])->middleware('log.admin:edit,修改矿池'); // 修改
                    Route::get('/detail/{id}', [MiningPoolController::class, 'show']);
                    Route::delete('/{id}', [MiningPoolController::class, 'destroy'])->middleware('log.admin:edit,删除矿池');
                    //周期列表
                    Route::post('/cycle_item/save/{id}', [MiningPoolController::class, 'saveMiningPoolCycleItem']);
                    Route::delete('/cycle_item/{id}', [MiningPoolController::class, 'destroyMiningPoolCycleItem']);
                });
            }
        );
        //空投
        Route::prefix('/air_drop')->group(function () {
            Route::get('/index', [AirDropController::class, 'index']);
            Route::post('/save', [AirDropController::class, 'save'])->middleware('log.admin:edit,新增空投'); // 新增
            Route::put('/save/{id}', [AirDropController::class, 'save'])->middleware('log.admin:edit,修改空投'); // 修改

        });


        Route::prefix('/loan-pools')->group(
            function () {
                Route::get('/order_list', [LoanPoolController::class, 'getOrderList']);
                //   Route::get('/order_award_list', [MiningPoolController::class, 'getOrderAwardList']);
                //    Route::get('/cycle_item/list', [MiningPoolController::class, 'getMiningPoolCycleItemList']);
                Route::middleware('check.super.admin')->group(function () {
                    Route::get('/list', [LoanPoolController::class, 'index']);
                    Route::post('/save', [LoanPoolController::class, 'save'])->middleware('log.admin:edit,新增借款矿池'); // 新增
                    Route::post('/save/{id}', [LoanPoolController::class, 'save'])->middleware('log.admin:edit,修改借款矿池'); // 修改
                    Route::get('/detail/{id}', [LoanPoolController::class, 'show']);
                    Route::delete('/{id}', [LoanPoolController::class, 'destroy'])->middleware('log.admin:edit,删除借款矿池');
                });
            }
        );


        //用户管理
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
                Route::post('/set/remark', [UserController::class, 'setRemark'])->middleware('log.admin:edit,设置备注'); //设置会员等级
                Route::post('/set/can_exchange', [UserController::class, 'setCanExchange'])->middleware('log.admin:edit,兑换功能'); //冻结用户
                Route::post('/change/{state}', [UserController::class, 'changeState'])->middleware('log.admin:edit,兑换功能'); //冻结用户

            });
        });


        //财务中心
        Route::prefix('/financial')->group(
            function () {
                Route::any('/recharge_list', [FinancialController::class, 'rechargeList']);
                Route::any('/withdrawal_list', [FinancialController::class, 'withdrawalList']);
                //
                Route::any('/user_asset_log', [FinancialController::class, 'UserAssetLogList']);
            }
        );


        //币种管理
        Route::prefix('/currencies')->group(
            function () {
                Route::get('/', [CurrencyController::class, 'index']);
                Route::post('/save', [CurrencyController::class, 'save'])->middleware('log.admin:edit,新增币种'); // 新增
                Route::any('/save/{id}', [CurrencyController::class, 'save'])->middleware('log.admin:edit,修改币种'); // 修改
                //Route::get('/{id}', [CurrencyController::class, 'show']);
                Route::delete('/{id}', [CurrencyController::class, 'destroy'])->middleware('log.admin:edit,删除币种');
            }
        );
        //菜单管理
        Route::prefix('/menus')->group(
            function () {
                Route::get('/', [MenuController::class, 'getMenus']);
                Route::post('/save', [MenuController::class, 'saveMenus']); // 新增
                Route::post('/save/{id}', [MenuController::class, 'saveMenus']); // 修改
                Route::get('/{id}', [MenuController::class, 'show']);
                Route::delete('/{id}', [MenuController::class, 'deleteMenus']);
            }
        );
        //角色管理
        Route::prefix('/roles')->group(
            function () {
                Route::get('/', [MenuController::class, 'role_list']);
                Route::post('/save', [MenuController::class, 'saveRole'])->middleware('log.admin:edit,新增角色'); // 新增
                Route::post('/save/{id}', [MenuController::class, 'saveRole'])->middleware('log.admin:edit,修改角色'); // 修改
                //删除
                // Route::delete('/{id}', [MenuController::class, 'deleteRole']);
                //新增后台账号
                Route::post('/save_admin/{id}', [MenuController::class, 'saveAdmin'])->middleware('log.admin:edit,新增后台账号'); // 新增
                Route::get('/admin_list', [MenuController::class, 'adminList']);

                //创建谷歌密钥
                Route::get('/generate_google_2fa_secret', [MenuController::class, 'generateGoogle2faSecret']);
                //获取密钥二维码
                Route::get('/get_google_2fa_qrcode', [MenuController::class, 'getGoogle2faQrCode']);
                //清chu谷歌密钥
                Route::get('/clear_google_2fa_secret', [MenuController::class, 'clearGoogle2faSecret'])->middleware('log.admin:edit,重置谷歌密钥');
            }
        );
        Route::prefix('/membership_levels')->group(
            function () {
                Route::apiResource('index', MembershipLevelController::class);
            }
        );
        //系统配置
        Route::prefix('/system_configs')->group(
            function () {
                Route::middleware('check.super.admin')->group(function () {
                    Route::apiResource('index', SystemConfigController::class);
                    Route::any('/update_configs', [SystemConfigController::class, 'updateConfigs'])->middleware('log.admin:edit,修改配置');
                    Route::any('/get_configs', [SystemConfigController::class, 'getConfigs']);
                    Route::any('/get_categories', [SystemConfigController::class, 'getCategories']);
                    Route::any('/get_configs_by_category', [SystemConfigController::class, 'getConfigsByCategory']);
                });
            }
        );


        //提现管理
        Route::prefix('/withdrawal-orders')->group(
            function () {
                Route::apiResource('index', WithdrawalOrderController::class);
                Route::post('/process/{id}', [WithdrawalOrderController::class, 'processWithdrawal'])->middleware('redis.lock:id');
                Route::post('/reject/{id}', [WithdrawalOrderController::class, 'rejectWithdrawal'])->middleware('redis.lock:id');
                Route::post('/pending_list', [WithdrawalOrderController::class, 'pendingList']);
            }
        );
        //数据统计
        Route::prefix('/statistics')->group(
            function () {
                Route::post('/user', [StatisticsController::class, 'userStatistics']);
                Route::post('/recharge', [StatisticsController::class, 'rechargeStatistics']);
                Route::post('/withdraw', [StatisticsController::class, 'withdrawStatistics']);
                Route::post('/all', [StatisticsController::class, 'allStatistics']);
            }
        );


        //公告
        Route::prefix('notices')->group(function () {
            Route::get('/index', [NoticeController::class, 'index']);
            Route::post('/save', [NoticeController::class, 'store'])->middleware('log.admin:edit,新增公告'); // 新增
            Route::get('/details/{id}', [NoticeController::class, 'details']);
            Route::delete('/{id}', [NoticeController::class, 'destroy'])->middleware('log.admin:edit,删除公告');
        });
    });
});
