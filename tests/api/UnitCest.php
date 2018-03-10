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
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'code' => 'un1', 'description' => 'un1 desc'],
                ['id' => 2, 'code' => 'un2', 'description' => 'un2 desc'],
            ],
        ]);
    }

    public function createUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $numOfRecords = $I->grabNumRecords(Unit::TABLE);
        $I->sendPOST($this->baseUrl, [
            'code'        => 'xxx',
            'description' => 'xxx desc',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => ['code' => 'xxx', 'description' => 'xxx desc'],
        ]);
        $I->seeNumRecords($numOfRecords + 1, Unit::TABLE);
    }

    public function createUnitWithAlreadyExistCode(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $numOfRecords = $I->grabNumRecords(Unit::TABLE);
        $I->sendPOST($this->baseUrl, [
            'code'        => 'un1',
            'description' => 'duplicated',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('code');
        verify($response['data']['code'])->contains('The code has already been taken.');
        $I->seeNumRecords($numOfRecords, Unit::TABLE);
    }

    public function createUnitWithTooLongCode(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $numOfRecords = $I->grabNumRecords(Unit::TABLE);
        $I->sendPOST($this->baseUrl, [
            'code'        => 'way too long unit code',
            'description' => 'super long unit',
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('code');
        verify($response['data']['code'])->contains('The code may not be greater than 3 characters.');
        $I->seeNumRecords($numOfRecords, Unit::TABLE);
    }

    public function updateUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendPUT("{$this->baseUrl}/1", [
            'code'        => 'cj1',
            'description' => 'updated',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('success');
        verify('Should be able to update description.', $response['data']['description'])->equals('updated');
        verify('Should be unable to update code.', $response['data']['code'])->equals('un1');
        $I->seeInDatabase(Unit::TABLE, [
            'id'          => 1,
            'code'        => 'un1',
            'description' => 'updated',
        ]);
    }

    public function deleteUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendDELETE("{$this->baseUrl}/1");
        $I->seeResponseCodeIs(200);
        $I->dontSeeInDatabase(Unit::TABLE, ['id' => 1]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => ['id' => 1, 'description' => 'un1 desc'],
        ]);
    }
}
