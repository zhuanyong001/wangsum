<?php

namespace App\Jobs;

use App\Dao\UserAssetDao;
use App\Models\AirDropLog;
use App\Models\AirDropOrder;
use App\Models\MiningPoolOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AirDropJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $airDropOrder;
    protected $miningPoolOrder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AirDropOrder $airDropOrder, MiningPoolOrder $miningPoolOrder)
    {
        $this->airDropOrder = $airDropOrder;
        $this->miningPoolOrder = $miningPoolOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $airDropOrder = $this->airDropOrder;
        $miningPoolOrder = $this->miningPoolOrder;

        //空投
        //计算空投金额(美金)
        $air_usd_amount = $airDropOrder->is_proportion
            ? bcmul($airDropOrder->amount_value, $miningPoolOrder->getUsdAmount(), 8)
            : $airDropOrder->amount_value;

        //计算代币数量，使用 BCMath 确保精度
        $air_amount = bcdiv($air_usd_amount, $airDropOrder->currency->price, 8);

        try {
            DB::transaction(function () use ($airDropOrder, $miningPoolOrder, $air_amount) {
                //创建空投记录
                $airDropLog = new AirDropLog();
                $airDropLog->order_no = $airDropOrder->order_no . '_' . $miningPoolOrder->user_id; //单号标识 空投订单号_用户ID //防止重复空投
                $airDropLog->user_id = $miningPoolOrder->user_id;
                $airDropLog->air_drop_order_id = $airDropOrder->id;
                $airDropLog->mining_pool_order_id = $miningPoolOrder->id;
                $airDropLog->amount = $air_amount;
                $airDropLog->currency_id = $airDropOrder->currency_id;
                $airDropLog->save();
                //更新用户资产
                $userAssetDao = new UserAssetDao();
                $userAsset = $userAssetDao->getAssetsByCurrency($miningPoolOrder->user_id, $airDropOrder->currency_id);
                $userAssetDao->updateUserAsset($userAsset, $air_amount, UserAssetDao::TYPE_AIR_DROP, '空投:' . $airDropOrder->id);
            });
        } catch (\Exception $e) {
            Log::error('空投失败', ['error' => $e->getMessage()]);
        }
    }
}
