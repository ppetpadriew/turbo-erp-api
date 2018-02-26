<?php

namespace App\Http\Middleware;


use App\Constants\ResponseStatus;
use App\Http\FormattedResponse;
use App\Http\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FormatResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        return (new ResponseFormatter)->format($response);
    }
}
