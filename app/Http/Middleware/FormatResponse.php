<?php

namespace App\Http\Middleware;


use App\Constants\ResponseStatus;
use App\Http\FormattedResponse;
use Closure;
use Illuminate\Http\Request;

class FormatResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof FormattedResponse) {
            return $response;
        }

        if (isset($response->exception)) {
            return new FormattedResponse(ResponseStatus::ERROR, $response->exception->getMessage());
        }

        return new FormattedResponse(ResponseStatus::SUCCESS, $response->original);
    }
}
