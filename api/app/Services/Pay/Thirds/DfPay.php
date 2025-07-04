<?php

namespace App\Services\Pay\Thirds;

use App\Models\Currency;
use App\Models\DepositOrder;
use App\Services\Pay\PayBase;
use App\Services\TronService;

class DfPay extends PayBase
{
    public $tag = 'dfpay';

    public function makeSF($order)
    {
        $address =  get_system_config('deposit_tron_address', 'TLdC9xAsu7tKwx4wCpici7jRiu6JgFKxVA');
        return $address;
    }

    public function SFNotify($data)
    {
        return 'success';
    }

    public function makeXF($order)
    {
        $tronService = new TronService();
        $amount = bcsub($order->amount, $order->fee, 8);
        $currency = Currency::find($order->currency_id);
        if ($currency->code !== 'TRX') {
            $result = $tronService->sendTransaction($order->destination_address, $amount, $currency->contract_address, $currency->unit); // 转换为最小单位
        } else {
            $result = $tronService->sendTransaction($order->destination_address, $amount); // 转换为最小单位
        }
        return  $result;
    }
}
