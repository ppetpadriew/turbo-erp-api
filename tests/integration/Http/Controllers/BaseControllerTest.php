<?php

namespace App\Tests\Integration\Http\Controllers;

use App\Database\Seeds\BaseControllerSeeder;
use App\Models\Model;
use App\Models\Unit;
use App\Tests\DummyController;
use App\Tests\Integration\Integration;
use Illuminate\Http\Request;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseControllerTest extends Integration
{
    public function testIndex()
    {
        (new BaseControllerSeeder)->run();
        $controller = new DummyController();

        $records = $controller->index();

        $this->assertNotEmpty($records);
        foreach ($records as $record) {
            $this->assertInstanceOf(Model::class, $record);
        }
    }

    public function testUpdate()
    {
        (new BaseControllerSeeder)->run();
        $controller = new DummyController();
        $requestMock = Mockery::mock(Request::class);
        $requestMock->allows([
            'toArray' => [
                'description' => 'updated'
            ]
        ]);

        $controller->update(1, $requestMock);

        $this->seeInDatabase(Unit::TABLE, [
            'id' => 1,
            'description' => 'updated'
        ]);
    }

    public function testUpdateWithException()
    {
        (new BaseControllerSeeder)->run();
        $controller = new DummyController();
        $requestMock = Mockery::mock(Request::class);
        $requestMock->allows([
            'toArray' => [
                'description' => 'updated'
            ]
        ]);
        $this->expectException(HttpException::class);

        $controller->update(0, $requestMock);
    }

    public function testCreate()
    {
        (new BaseControllerSeeder)->run();
        $controller = new DummyController();
        $requestMock = Mockery::mock(Request::class);
        $payload = [
            'code' => 'dmy',
            'description' => 'dummy unit'
        ];
        $requestMock->allows([
            'toArray' => $payload
        ]);

        $controller->create($requestMock);

        $this->seeInDatabase(Unit::TABLE, $payload);
    }

    public function testDelete()
    {
        (new BaseControllerSeeder)->run();
        $controller = new DummyController();

        $controller->delete(1);

        $this->notSeeInDatabase(Unit::TABLE, [
            'id' => 1
        ]);
    }


    public function testDeleteWithException()
    {
        (new BaseControllerSeeder)->run();
        $controller = new DummyController();
        $this->expectException(HttpException::class);

        $controller->delete(0);
    }
}
