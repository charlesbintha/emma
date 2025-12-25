<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log incoming request
        $requestData = [
            'timestamp' => now()->toDateTimeString(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $request->headers->all(),
            'query_params' => $request->query(),
            'body' => $request->all(),
            'user_id' => $request->user() ? $request->user()->id : null,
        ];

        Log::channel('api')->info('API Request', $requestData);

        // Process request
        $response = $next($request);

        // Log response
        $responseData = [
            'timestamp' => now()->toDateTimeString(),
            'status_code' => $response->getStatusCode(),
            'content' => $response->getContent(),
        ];

        Log::channel('api')->info('API Response', $responseData);

        return $response;
    }
}
