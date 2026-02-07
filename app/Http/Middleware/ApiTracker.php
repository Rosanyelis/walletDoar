<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiTracker
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
        $response = $next($request);

        // Only track API requests
        if ($request->is('api/*')) {
            try {
                $data = [
                    'time' => now()->toDateTimeString(),
                    'method' => $request->method(),
                    'uri' => $request->path(),
                    'ip' => $request->ip(),
                    'status' => $response->getStatusCode(),
                    'user_agent' => substr($request->userAgent(), 0, 100),
                ];

                Log::channel('api_tracker')->info(json_encode($data));
            } catch (\Exception $e) {
                // Silently fail if logging fails
            }
        }

        return $response;
    }
}
