<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\AdminLog as ModelsAdminLog;
use Closure;

class AdminLog
{
    protected $type;
    protected $note;

    public function handle($request, Closure $next, $type = 'default', $note = '')
    {
        $this->type = $type;
        $this->note = $note;

        $response = $next($request);


        // 记录请求和响应到数据库
        $row = $this->logToDatabase($request, $response);
        if ($type == 'login') {
            $row->admin_id = Admin::where('username', $request->username)->value('id') ?? 0;
        } else {
            $row->admin_id = $request->user()->id;
        }
        $row->save();


        return $response;
    }

    protected function logToDatabase($request, $response)
    {
        return ModelsAdminLog::create([
            'admin_id' =>  0,
            'type' => $this->type,
            'note' => $this->note,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'status' => $response->status(),
            'response' => $response->getContent(),
            'parameters' => json_encode($request->all()), // 将请求参数编码为 JSON
        ]);
    }
}
