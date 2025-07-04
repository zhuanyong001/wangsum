<?php

namespace App\Services;

use Elliptic\EC;
use GuzzleHttp\Client;
use IEXBase\TronAPI\Support\Keccak;
use IEXBase\TronAPI\Tron;
use StephenHill\Base58;

use Tron\Address;
use Tron\Api;
use Tron\TRC20;
use Tron\TRX;

class TronService
{
    protected $client;
    protected $apiUrl;
    protected $privateKey;
    protected $base58;
    // protected $address;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => env('SSL_VERIFY', true),
        ]);
        $this->apiUrl = get_system_config('TRON_API_URL', 'https://nile.trongrid.io'); // 使用 Nile Testnet
        $this->privateKey = get_system_config('TRON_PRIVATE_KEY', 'your_private_key');
        // $this->address = 'TLdC9xAsu7tKwx4wCpici7jRiu6JgFKxVA';
        $this->base58 = new Base58();
    }

    private function getTRC20($contract)
    {
        $api = new Api(new Client(['base_uri' => $this->apiUrl, 'verify' => false]));
        $trxWallet = new TRC20($api, $contract);
        return $trxWallet;
    }

    private function getTRX()
    {
        $api = new Api(new Client(['base_uri' => $this->apiUrl, 'verify' => false]));
        $trxWallet = new TRX($api);
        return $trxWallet;
    }


    public function getTransactionInfoById($transactionId)
    {
        $response = $this->client->get("{$this->apiUrl}/wallet/gettransactionbyid", [
            'query' => ['value' => $transactionId]
        ]);
        return json_decode($response->getBody(), true);
    }

    public function hexStringToBase58($hexString)
    {
        $addressHex = '41' . substr($hexString, 2);
        $checksum = substr(hash('sha256', hash('sha256', hex2bin($addressHex), true)), 0, 8);
        $addressWithChecksum = $addressHex . $checksum;
        return $this->base58->encode(hex2bin($addressWithChecksum));
    }

    public function validateTransactionAddress($transactionId)
    {
        $transactionInfo = $this->getTransactionInfoById($transactionId);

        if (isset($transactionInfo['ret'])) {

            $status = $transactionInfo['ret'][0]['contractRet'];
            if ($status == 'NOT_FOUND') {
                return false;
            }
            $contract = $transactionInfo['raw_data']['contract'][0];
            if ($contract['type'] == 'TransferContract') {
                $fromAddress = $this->hexStringToBase58($contract['parameter']['value']['owner_address']);
                $toAddressHex = $contract['parameter']['value']['to_address'];
                $toAddress = $this->hexStringToBase58($toAddressHex);
                $amount = $contract['parameter']['value']['amount'];
                return [
                    'from_address' => $fromAddress,
                    'to_address' => $toAddress,
                    'amount' => $amount,
                    'status' => $status,
                    'contract_address' => ''
                ];
            } else {
                $data = $contract['parameter']['value']['data'];
                // 解析方法ID和参数
                $methodId = substr($data, 0, 8);
                $toAddressHex = substr($data, 32, 40);

                $amountHex = substr($data, 72, 64);
                if ($methodId == 'a9059cbb') {
                    $fromAddress = $this->hexStringToBase58($contract['parameter']['value']['owner_address']);
                    $toAddress = $this->hexStringToBase58('41' . $toAddressHex);
                    $contractAddress = $this->hexStringToBase58($contract['parameter']['value']['contract_address']);
                    $amount = hexdec($amountHex);
                    return [
                        'from_address' => $fromAddress,
                        'to_address' => $toAddress,
                        'contract_address' => $contractAddress,
                        'amount' => $amount,
                        'status' => $status
                    ];
                }
            }
        }
        return false;
    }
    public function sendTransaction($toAddress, $amount, $contractAddress = null, $unit = 1)
    {
        if ($contractAddress) {
            $tron = $this->getTRC20(['contract_address' => $contractAddress, 'decimals' => log10($unit)]);
        } else {
            $tron = $this->getTRX();
        }

        $from = $tron->privateKeyToAddress($this->privateKey);
        $to = new Address(
            $toAddress,
            '',
            $tron->tron->address2HexString($toAddress)
        );
        $transferData = $tron->transfer($from, $to, $amount);
        if ($transferData) {
            return [
                'txID' => $transferData->txID,
                'result' => true
            ];
        } else {
            return [
                'result' => false
            ];
        }
    }
    protected function getAddressFromPrivateKey($privateKey)
    {
        // 使用 TronGrid API 获取地址
        $response = $this->client->post("{$this->apiUrl}/wallet/getaddress", [
            'json' => ['privateKey' => $privateKey]
        ]);
        $result = json_decode($response->getBody(), true);
        return $result['address']['base58'];
    }

    function tronAddressFromPublicKey($publicKey)
    {
        // 计算公钥的Keccak-256哈希值
        $hash = Keccak::hash(substr(hex2bin($publicKey), 1), 256);

        // 取哈希的最后20字节
        $address = substr($hash, -40);

        // 添加TRON地址的前缀 "41"
        return '41' . $address;
    }
    function verifyTronSignature($message, $signature, $address)
    {
        $ec = new EC('secp256k1');
        // 处理签名前的消息：添加前缀
        $prefix = "\x19TRON Signed Message:\n32{$message}";
        // 移除签名的0x前缀
        if (strpos($signature, '0x') === 0) {
            $signature = substr($signature, 2);
        }
        // 从签名中提取 r, s 和 v
        $r = substr($signature, 0, 64);
        $s = substr($signature, 64, 64);
        $v = hexdec(substr($signature, 128, 2)) - 27;
        // 计算消息哈希
        $msgHash = Keccak::hash($prefix, 256);
        // 恢复公钥
        $signature = ['r' => $r, 's' => $s];
        $recoveredKey = $ec->recoverPubKey($msgHash, $signature, $v);
        $publicKey = $recoveredKey->encode('hex');

        // 将公钥转换为TRON地址
        $recoveredHexAddress  = $this->tronAddressFromPublicKey($publicKey);
        //$recoveredBase58Address  =  $this->hexStringToBase58($recoveredHexAddress);
        $address = (new Tron())->address2HexString($address);


        // 比较恢复的地址和提供的地址
        return hash_equals($recoveredHexAddress, $address);
    }
}
