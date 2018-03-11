<?php

namespace App\Tests\Unit\Http;


use App\Tests\DummyController;
use App\Tests\Unit\Unit;

class BaseControllerTest extends Unit
{
    public function testConstruct()
    {
        $this->specify('It should throw an exception when model class is empty.', function () {
            new DummyController();
        }, [
            'throws' => \Exception::class,
        ]);
    }
}
