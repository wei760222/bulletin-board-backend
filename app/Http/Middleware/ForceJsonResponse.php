<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 強制設置 Accept 標頭為 application/json
        $request->headers->set('Accept', 'application/json');
        
        // 獲取響應
        $response = $next($request);
        
        // 確保響應是 JSON 格式
        if ($response instanceof \Illuminate\Http\Response) {
            $response->header('Content-Type', 'application/json');
        }
        
        return $response;
    }
}
