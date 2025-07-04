<?php

namespace App\Services;

use GuzzleHttp\Client;

class CoinMarketCapService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => env('SSL_VERIFY', true),
            //'proxy' => 'http://127.0.0.1:10809', // 设置代理
        ]);
        $keys = get_system_config('CMC_API_KEY', '0e1257fa-82e6-4b74-835c-281a08067e37|0e1257fa-82e6-4b74-835c-281a08067e37');
        //按|分割
        $keys  = explode('|', $keys);
        $this->apiKey = $keys[array_rand($keys)]; // 确保在 .env 文件中定义了你的 CMC_API_KEY

        // $this->apiKey = get_system_config('CMC_API_KEY', '0e1257fa-82e6-4b74-835c-281a08067e37'); // 确保在 .env 文件中定义了你的 CMC_API_KEY
    }

    public function getCoinPrices(array $symbols)
    {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
        $symbolString = implode(',', $symbols);
        $response = $this->client->get($url, [
            'query' => [
                'symbol' => $symbolString
            ],
            'headers' => [
                'X-CMC_PRO_API_KEY' => $this->apiKey
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $prices = [];

        foreach ($symbols as $symbol) {
            if (isset($data['data'][$symbol]['quote']['USD']['price'])) {
                $prices[$symbol] = [
                    'price' => $data['data'][$symbol]['quote']['USD']['price'],
                    'percent_change_24h' => $data['data'][$symbol]['quote']['USD']['percent_change_24h']
                ];
            } else {
                $prices[$symbol] = null;
            }
        }

        return $prices;
    }
}
