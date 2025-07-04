<?php

namespace App\Services\Pay\Thirds;

use App\Dao\UserAssetDao;
use App\Models\Currency;
use App\Models\DepositOrder;
use App\Models\WithdrawalOrder;
use App\Services\Pay\PayBase;
use App\Services\Pay\Thirds\helper\RSA2048Encrypt;
use Illuminate\Support\Facades\Cache;

class AnonyPay extends PayBase
{
    public $tag = 'anonypay';

    public $host = 'https://api.anonypay.io/api/merchant/';
    public $id, $secret, $public_t, $private_u, $callbackUrl;

    public function __construct()
    {
        $this->id = env('ANONY_PAY_ID');
        $this->secret = env('ANONY_PAY_SECRET');
        $this->public_t = base64_decode(env('ANONY_PAY_PUBLIC_T'));
        $this->private_u = base64_decode(env('ANONY_PAY_PRIVATE_U'));
        $this->callbackUrl = env('ANONY_PAY_CALLBACK_URL');
    }
    public function checkSign()
    {
        $headers = request()->header();
        $body = request()->getContent();
        if (!isset($headers['anony-sign']) || !$body) {
            return false;
        }
        $this->log('commonNotify回调数据签名验证', [$headers, $body]);
        $sign = $headers['anony-sign'][0] ?? '';
        return RSA2048Encrypt::verify($body, $this->public_t, $sign);
    }

    public function makeSF($order)
    {
        $order = $order->user->tron_address;
        $data = [
            'userOrder' => $order,
            'addressType' => 'trx',
            'callbackUrl' =>  $this->callbackUrl,
        ];
        $this->log('makeSF请求数据', $data);
        $res =  $this->signAndPost($this->host . 'createAddress', $data);
        $this->log('makeSF返回数据',  [$res]);
        $address = '';
        if ($res) {
            $res = json_decode($res, true);
            if ($res['message'] == 'success' && $res['code'] == 10000) {
                $res_data =  $this->decryptData($res['data']);
                if ($res_data) {
                    $address = json_decode($res_data, true)['address'] ?? false;
                }
            }
        }
        return $address;
    }

    public function SFNotify($data)
    {
        $this->log('SFNotify回调数据', $data);
        $coin_type = ['trx' => 'TRX', 'trx.usdt' => 'USDT'][$data['currency']] ?? '';
        $amount = $data['amount'];
        $status = true;
        $txid = $data['txid'];
        if (!$txid) {
            return 'fail';
        }
        $order = DepositOrder::where('transaction_id', $txid)->first();
        if (!$order) {
            return 'fail';
        }
        $userAssetDao = new UserAssetDao();
        $userAssetDao->DepositOrderPaySuccess($order->order_no, $coin_type, $amount, $status);
        return 'SUCCESS';
    }

    public function makeXF($order)
    {
        $amount = bcsub($order->amount, $order->fee, 5);
        $data = [
            'userOrder' => $order->order_no,
            'address' => $order->destination_address,
            'amount' =>  $amount,
            'withdrawType' => 'financialConfirmation'
        ];

        $currency = Currency::find($order->currency_id);
        if ($currency->code == 'USDT') {
            $data['currency'] = 'trx.usdt';
        } else if ($currency->code == 'TRX') {
            $data['currency'] = 'trx';
        } else {
            $result =  ['result' => false, 'msg' => 'currency not support'];
            return $result;
        }
        $res = $this->signAndPost($this->host . 'createWithdrawOrder', $data);
        $this->log('makeXF返回数据',  [$res]);
        $result = ['result' => false];
        if ($res) {
            $res = json_decode($res, true);
            if ($res['message'] == 'success' && $res['code'] == 10000) {
                $res_data = $this->decryptData($res['data']);
                $result =  ['result' => true, 'txID' => $res_data['txid'] ?? ''];
            }
        }
        return $result;
    }

    public function commonNotify($data)
    {
        if (!$this->checkSign()) {
            return 'sign error';
        }
        $body = request()->getContent();
        $data = json_decode($this->decryptData($body), true);
        if (!$data) {
            return 'data error';
        }
        if (!isset($data['businessType'])) {
            return 'type error';
        }
        if ($data['businessType'] == 'deposit') {
            return $this->SFNotify($data);
        } else if ($data['businessType'] == 'withdraw') {
            return $this->XFNotify($data);
        } else if ($data['businessType'] == 'withdrawalPendingConfirm') {
            return $this->confirmNotify($data);
        } else {
            $this->log('Notify处理失败:businessType错误', [$data]);
            return 'fail';
        }
        return 'fail';
    }

    public function confirmNotify($data)
    {
        $this->log('确认通知', $data);
        $order_no = $data['userOrder'];
        $address = $data['toAddress'];
        $order = WithdrawalOrder::where([
            'order_no' => $order_no,
            'destination_address' => $address,
            'status' => WithdrawalOrder::STATUS_PENDING
        ])->first();
        if ($order) {
            return 'SUCCESS';
        }
        return 'fail';
    }

    public function XfNotify($data)
    {
        $this->log('XFNotify回调数据', $data);

        if (in_array($data['orderStatus'], ['success', 'fail', 'reject'])) {
            $order_no = $data['userOrder'];
            $status = $data['orderStatus'] === 'success';
            $userAssetDao = new UserAssetDao();
            $res = $userAssetDao->WithdrawalOrderPaySuccess($order_no,  $data['txid'] ?? false, $status);
            $this->log('XFNotify处理结果:', ['info' => $res]);
            if ($res === true) {
                return 'SUCCESS';
            } else {
                return 'fail';
            }
        }
        return 'fail';
    }


    // 获取token
    public function getToken(array $data = [])
    {
        $token = Cache::get('anony_pay_token');
        if ($token) {
            return $token;
        }

        $parms = md5($this->id . '&' . $this->secret);
        // 添加请求头
        $header = array(
            'ANONY-APP-ID' => $this->id,
            'Authorization' => 'auth ' . $parms
        );

        $res = $this->post_json($this->host . 'getToken', $data, $header);
        if ($res) {
            $res = json_decode($res, true);
            if ($res['message'] == 'success' && $res['code'] == 10000) {
                $data =   $this->decryptData($res['data']);
                if ($data) {
                    $token = json_decode($data, true)['token'] ?? false;
                    Cache::put('anony_pay_token', $token, 60 * 60 * 1);
                    return $token;
                }
            }
        }
        return false;
    }


    // 签名并请求
    public function signAndPost($url, $data, $token = null)
    {
        $encryptedData = RSA2048Encrypt::encrypt(json_encode($data), $this->public_t);
        // 签名
        $sign = RSA2048Encrypt::sign($encryptedData, $this->private_u);

        //var_dump(RSA2048Encrypt::verify($encryptedData, $this->public_u, $sign));

        // 如果token为空则获取token
        if (empty($token)) {
            $token = $this->getToken([]);
            if (!$token) {
                return '获取token失败';
            }
        }
        // 添加请求头
        $header = array(
            'ANONY-APP-ID' => $this->id,
            'ANONY-SIGN' => $sign,
            'ANONY-TOKEN' => $token,
        );
        $this->log('url', [$url]);
        $this->log('请求数据', $data);
        $this->log('请求头', $header);
        return $this->post_json($url, $encryptedData, $header, true);
    }

    // 解密数据
    public function decryptData($data)
    {
        return RSA2048Encrypt::decrypt($data, $this->private_u);
    }
}
