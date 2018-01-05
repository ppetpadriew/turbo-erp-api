<?php


use App\Database\Seeds\UnitControllerSeeder;
use App\Models\Unit;

class UnitCest
{
    private $baseUrl = '/units';

    public function getUnits(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendGET($this->baseUrl);
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonEquals([
            ['id' => 1, 'code' => 'un1', 'description' => 'un1 desc'],
            ['id' => 2, 'code' => 'un2', 'description' => 'un2 desc'],
        ]);
    }

    public function createUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendPOST($this->baseUrl, [
            'code'        => 'xxx',
            'description' => 'xxx desc',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['code' => 'xxx', 'description' => 'xxx desc']);
    }

    public function updateUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendPUT("{$this->baseUrl}/1", [
            'description' => 'updated',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => 1, 'description' => 'updated']);
    }

    public function updateNonExistentUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendPUT("{$this->baseUrl}/99", [
            'description' => 'updated',
        ]);
        $I->seeResponseCodeIs(404);
//        $I->seeResponseIsJson();
//        $I->seeResponseContainsJson([
//            'status' => 'error',
//        ]);
    }

    public function deleteUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendDELETE("{$this->baseUrl}/1");
        $I->seeResponseCodeIs(200);
        $I->dontSeeInDatabase(Unit::TABLE, ['id' => 1]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => 1, 'description' => 'un1 desc']);
    }

    public function deleteNonExistentUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendDELETE("{$this->baseUrl}/99");
        $I->seeResponseCodeIs(404);
//        $I->seeResponseIsJson();
//        $I->seeResponseContainsJson([
//            'status' => 'error',
//        ]);
    }
}
