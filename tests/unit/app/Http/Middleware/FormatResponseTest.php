<?php

namespace App\Tests\Unit\Http\Middleware;

use App\Http\FormattedResponse;
use App\Http\Middleware\FormatResponse;
use App\Tests\Unit\Unit;
use Codeception\Stub;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FormatResponseTest extends Unit
{
    public function testHandle()
    {
        $this->specify('It should do nothing when response is an instance of FormattedResponse.', function () {
            $mockRes = Stub::makeEmpty(FormattedResponse::class);
            $mockReq = Stub::makeEmpty(Request::class);

            $result = (new FormatResponse)->handle($mockReq, function () use ($mockRes) {
                return $mockRes;
            });

            verify($result)->same($mockRes);
        });

        $this->specify('It should return instance of FormattedResponse with error when response has exception.',
            function () {
                $mockRes = Stub::makeEmpty(Response::class, [
                    'exception' => $exception = new \InvalidArgumentException('dummy message'),
                ]);
                $mockReq = Stub::makeEmpty(Request::class);

                $result = (new FormatResponse)->handle($mockReq, function () use ($mockRes) {
                    return $mockRes;
                });

                verify('Should use "error" as status and message from exception as "message".', $result->original)
                    ->same([
                        'status'  => 'error',
                        'message' => $exception->getMessage(),
                    ]);
            });

        $this->specify('It should return instance of FormattedResponse with data when exception is not set in response.', function () {
            $mockRes = Stub::makeEmpty(Response::class, [
                'original' => ['name' => 'dummy'],
            ]);
            $mockReq = Stub::makeEmpty(Request::class);

            $result = (new FormatResponse)->handle($mockReq, function () use ($mockRes) {
                return $mockRes;
            });

            verify('Should use "success" as status and original as data.', $result->original)
                ->same([
                    'status' => 'success',
                    'data'   => $mockRes->original,
                ]);
        });
    }
}
