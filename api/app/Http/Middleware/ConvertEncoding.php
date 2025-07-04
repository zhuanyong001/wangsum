<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConvertEncoding
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->getMethod() == "POST") {
            $input = $request->all();
            array_walk_recursive($input, function (&$value) {
                $value = mb_convert_encoding($value, 'UTF-8', 'GBK');
            });
            $request->merge($input);
        }

        $response = $next($request);

        if (method_exists($response, 'setContent')) {
            $content = $response->getContent();
            $content = mb_convert_encoding($content, 'GBK', 'UTF-8');
            $response->setContent($content);
        }

        return $response;
    }
}
