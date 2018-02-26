<?php

namespace App\Tests\Unit\Http;

use App\Http\ResponseFormatter;
use App\Tests\Unit\Unit;
use Illuminate\Http\Response;
use Mockery;

class ResponseFormatterTest extends Unit
{
    public function testFormat()
    {
        $this->specify('It should set status = "success" and use original content as data when original response has http status = 200.',
            function () {
                $spyRes = Mockery::spy(Response::class, [
                    'header'             => null,
                    'status'             => 200,
                    'getOriginalContent' => $data = ['name' => 'John'],
                ]);

                (new ResponseFormatter)->format($spyRes);

                $spyRes
                    ->shouldHaveReceived('setContent')
                    ->with(['status' => 'success', 'data' => $data]);
            });

        $this->specify('It should set status = "fail" and use original content as data when original response has http status = 400', function () {
            $spyRes = Mockery::spy(Response::class, [
                'header'             => null,
                'status'             => 400,
                'getOriginalContent' => $data = ['name' => 'Name is required.'],
            ]);

            (new ResponseFormatter)->format($spyRes);

            $spyRes
                ->shouldHaveReceived('setContent')
                ->with(['status' => 'fail', 'data' => $data]);
        });

        $this->specify('It should set status = "error" and use the message from exception when original response has http status other than 200 and 400.', function () {
            $spyRes = Mockery::spy(Response::class, [
                'header' => null,
                'status' => 404,
            ]);
            $spyRes->exception = $exception = new \Exception('Not found.');

            (new ResponseFormatter)->format($spyRes);

            $spyRes
                ->shouldHaveReceived('setContent')
                ->with(['status' => 'error', 'message' => $exception->getMessage()]);
        });

        $this->specify('It should do nothing when response is already formatted.', function ($content) {
            $spyRes = Mockery::spy(Response::class, [
                'header'             => null,
                'getOriginalContent' => $content,
            ]);

            (new ResponseFormatter)->format($spyRes);

            $spyRes->shouldNotHaveReceived('setContent');
        }, [
            'examples' => [
                [
                    'content' => [
                        'status' => 'success',
                        'data'   => ['name' => 'John'],
                    ],
                ],
                [
                    'content' => [
                        'status'  => 'error',
                        'message' => 'some exception message',
                    ],
                ],
            ],
        ]);
    }
}
