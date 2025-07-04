<?

namespace App\Services\Pay;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PayBase
{
    public $tag = 'pay';

    public function pay()
    {
        echo '支付';
    }


    public function log($msg, $data = [])
    {
        $logPath = storage_path('logs/pay/' . $this->tag . '/pay.log');
        Log::build([
            'driver' => 'daily',
            'path' => $logPath,  // 使用动态路径
            'level' => 'info',
        ])->info($msg, $data);
    }


    public function post_json($url, $data, $headers = [], $is_json = false)
    {
        $client = new Client(['verify' => env('SSL_VERIFY', true)]);
        $headers = array_merge($headers, [
            'Content-Type' => 'application/json'
        ]);
        $response = $client->post($url, [
            'headers' => $headers,
            'body' => $is_json ? $data : json_encode($data)
        ]);
        return $response->getBody()->getContents();
    }
}
