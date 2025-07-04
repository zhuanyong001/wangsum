<?php

namespace App\Services\Pay;

use App\Models\DepositOrder;
use App\Services\Pay\Thirds\AnonyPay;
use App\Services\Pay\Thirds\DfPay;
use App\Services\Pay\Thirds\NmPay;

class PayService
{
    private $tags = [
        'dfpay' => DfPay::class,
        'nmpay' => NmPay::class,
        'anonypay' => AnonyPay::class
    ];

    private $pay = null;
    public function __construct($pay_name)
    {
        if (!array_key_exists($pay_name, $this->tags)) {
            throw new \Exception('pay_name not exists');
        }
        $this->pay = new $this->tags[$pay_name]();
    }


    public function makeSFOrder($order)
    {

        try {
            $res = $this->pay->makeSF($order);
        } catch (\Exception $e) {
            $this->pay->log('makeXFOrder错误', ['info' => $e->getMessage()]);
            throw $e;
            return [
                'code' => 500,
                'data' => [],
                'msg' => $e->getMessage()
            ];
        }
        return [
            'code' => 200,
            'data' => $res,
            'msg' => ''
        ];
    }


    public function SFNotify($data)
    {
        $res = $this->pay->SFNotify($data);
        return $res;
    }

    public function makeXFOrder($order)
    {
        try {
            $res = $this->pay->makeXF($order);
        } catch (\Exception $e) {

            $this->pay->log('makeXFOrder错误', ['info' => $e->getMessage()]);
            return [
                'result' => false,
            ];
        }
        return $res;
    }

    public function XFNotify($data)
    {
        $res = $this->pay->XFNotify($data);
        return $res;
    }

    public function commonNotify($data)
    {
        $res = $this->pay->commonNotify($data);
        return $res;
    }
}
