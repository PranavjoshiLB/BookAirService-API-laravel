<?php

namespace App\Http\Middleware;

use Amadeus\Client\Session\Handler\HandlerFactory;
use App\Exceptions\ApiKeyException;
use Closure;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Perform action
        if ($request->hasHeader('x-api-key')) {
            $apiKey = $request->header('x-api-key');
            if ($apiKey == config('app.api_key')) {
                return $next($request);
            }


        }
        throw new ApiKeyException('Api key must be set');
    }
}
