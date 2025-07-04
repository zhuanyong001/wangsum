<?php

namespace App\Services\Pay\Thirds;

use App\Dao\UserAssetDao;
use App\Models\Currency;
use App\Models\DepositOrder;
use App\Models\WithdrawalOrder;
use App\Services\Pay\PayBase;

class NmPay extends PayBase
{
    public $tag = 'nmpay';

    public $host = 'https://anony.cash/shop';
    public $mid = '';
    public $token = '';


    public function __construct()
    {
        $this->mid = env('NM_PAY_MID', '10257');
        $this->token = env('NM_PAY_TOKEN', 'agnpstvzDJKMUX39');
    }

    public function sign(array $data)
    {

        $data = array_filter($data);
        ksort($data);
        return strtoupper(md5(urldecode(http_build_query($data) . '&token=' . $this->token)));
    }

    public function checkSign($data)
    {
        $in_sign = $data['sign'];
        unset($data['sign']);
        $data = array_filter($data);
        ksort($data);
        $sign = strtoupper(md5(urldecode(http_build_query($data) . '&token=' . $this->token)));
        return $in_sign == $sign ? true : false;
    }

    public function makeSF($order)
    {
        $order = $order->user->tron_address;
        $data = [
            'id' => $this->mid,
            'order' => $order,
        ];
        $data['sign'] = $this->sign($data);
        $this->log('makeSF请求数据', $data);
        $res =  $this->post_json($this->host . '/newAddress', $data);
        $this->log('makeSF返回数据',  [$res]);
        $res = json_decode($res, true);
        $address = '';
        if ($res['status'] == 'success' && $res['code'] == 10000) {
            $address =  $res['data']['address'];
        }

        // $address =  get_system_config('deposit_tron_address', 'TLdC9xAsu7tKwx4wCpici7jRiu6JgFKxVA');
        return $address;
    }

    public function SFNotify($data)
    {
        $this->log('SFNotify回调数据', $data);
        if (!$this->checkSign($data)) {
            return 'sign error';
        }
        $order_no = $data['data']['order'];
        $coin_type = $data['data']['coin_type'];
        $amount = $data['data']['amount'];
        $status = true;
        $txid = $data['data']['txid'];
        if (!$txid) {
            return 'fail';
        }
        $order = DepositOrder::where('transaction_id', $txid)->first();
        if (!$order) {
            return 'fail';
        }
        $userAssetDao = new UserAssetDao();
        $userAssetDao->DepositOrderPaySuccess($order->order_no, $coin_type, $amount, $status);
        return 'success';
    }

    public function makeXF($order)
    {
        $amount = bcsub($order->amount, $order->fee, 8);
        $data = [
            'id' => $this->mid,
            'order' => $order->order_no,
            'address' => $order->destination_address,
            'amount' =>  $amount,
        ];

        //多签接口
        $currency = Currency::find($order->currency_id);
        if ($currency->code == 'USDT') {
            $data['coin_type'] = 'USDT';
        } else if ($currency->code == 'TRX') {
            // $data['coin_type'] = 'TRX';
        } else {
            $result =  ['result' => false, 'msg' => 'currency not support'];
            return $result;
        }
        $data['sign'] = $this->sign($data);

        // $data['sign'] = $this->sign($data);
        $this->log('makeXF请求数据', $data);
        // $currency = Currency::find($order->currency_id);
        if ($currency->code == 'USDT') {
            $res = $this->post_json($this->host . '/withdraw/multsign', $data);
            //$res =  $this->post_json($this->host . '/withdraw/usdt', $data);
        } else if ($currency->code == 'TRX') {
            $res =  $this->post_json($this->host . '/withdraw/trx', $data);
        } else {
            $result =  ['result' => false, 'msg' => 'currency not support'];
        }


        $this->log('makeXF返回数据',  [$res]);
        $res = json_decode($res, true);
        $result = [];
        if ($res['status'] == 'success' && $res['code'] == 10000) {
            $result =  ['result' => true, 'txID' => $res['data']['txid'] ?? ''];
        } else {
            $result =  ['result' => false];
        }
        return $result;
    }

    public function commonNotify($data)
    {
        if (!isset($data['type'])) {
            return 'type error';
        }
        if ($data['type'] == 'deposit') {
            return $this->SFNotify($data);
        } else {
            return $this->XFNotify($data);
        }

        return 'success';
    }

    public function XfNotify($data)
    {
        $this->log('XFNotify回调数据', $data);

        if (!$this->checkSign($data)) {
            return 'sign error';
        }
        if (in_array($data['data']['status'], ['pass', 'reject'])) {
            $order_no = $data['data']['order'];
            $status = $data['data']['status'] == 'pass' ? true : false;
            $userAssetDao = new UserAssetDao();
            $res = $userAssetDao->WithdrawalOrderPaySuccess($order_no,  $data['data']['txid'] ?? false, $status);
            if ($res === true) {
                return 'success';
            } else {
                $this->log('XFNotify处理结果:', ['info' => $res]);
                return 'fail';
            }
        }
        return 'fail';
    }
}
