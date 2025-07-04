<?php

namespace App\Console\Commands;

use App\Models\MiningPoolAwardLog;
use App\Models\MiningPoolOrder;
use App\Models\User;
use App\Models\UserAsset;
use DateTime;
use Illuminate\Console\Command;

class getTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_test_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order_ids = MiningPoolOrder::where('status', 1)->pluck('id');
        foreach ($order_ids as $order_id) {
            $order = MiningPoolOrder::find($order_id);
            // echo '订单ID:' . $order_id . PHP_EOL;
            $days =  $this->calculateDaysFromDate($order->created_at);
            //  echo $days . PHP_EOL;
            //获取订单数据
            $count = MiningPoolAwardLog::where('mining_pool_order_id', $order_id)->count();
            //  echo $count . PHP_EOL;


            echo '订单ID:' . $order_id . '_' . $order->cycle . '_' . $days . '_' . $count . PHP_EOL;
        }
    }
    function calculateDaysFromDate($date)
    {
        try {
            // 将输入日期转换为 DateTime 对象
            $givenDate = new DateTime($date);
            // 获取当前日期
            $currentDate = new DateTime();
            // 计算日期差
            $interval = $givenDate->diff($currentDate);
            // 返回差异的天数
            return $interval->days;
        } catch (\Exception $e) {
            // 如果日期格式不正确，返回错误信息
            return "Invalid date format: " . $e->getMessage();
        }
    }
}
