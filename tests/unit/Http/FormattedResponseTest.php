<?php

namespace App\Tests\Unit\Http;

use App\Http\FormattedResponse;
use App\Tests\Unit\Unit;

class FormattedResponseTest extends Unit
{
    public function testConstruct()
    {
        $this->specify('It should throw an exception when invalid status is given.', function () {
            new FormattedResponse('invalid', []);
        }, [
            'throws' => \InvalidArgumentException::class,
        ]);

        $this->specify('It should set "message" to content when status = "error".', function () {
            $msg = 'Sample message';
            $res = new FormattedResponse('error', $msg);

            verify($res->original)->equals([
                'status'  => 'error',
                'message' => $msg,
            ]);
        });

        $this->specify('It should set "data" to content when status != "error".', function ($status) {
            $data = ['name' => 'dummy', 'age' => 10];
            $res = new FormattedResponse($status, $data);

            verify($res->original)->equals([
                'status' => $status,
                'data'   => $data,
            ]);
        }, [
            'examples' => [
                ['status' => 'fail'],
                ['status' => 'success'],
            ],
        ]);
    }
}
