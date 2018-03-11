<?php

namespace App\Tests\Api;

use ApiTester;
use App\Database\Seeds\UnitControllerSeeder;
use App\Models\Unit;
use App\Tests\ValidationMessage;

class UnitCest extends BaseCest
{
    protected function getBaseUrl(): string
    {
        return '/units';
    }

    public function testGetUnits(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();

        $I->sendGET($this->getBaseUrl());

        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonEquals([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'code' => 'un1', 'description' => 'un1 desc'],
                ['id' => 2, 'code' => 'un2', 'description' => 'un2 desc'],
            ],
        ]);
    }

    public function testGetUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $row = $I->grabRecord(Unit::TABLE, ['id' => 1]);

        $I->sendGET("{$this->getBaseUrl()}/1");

        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonEquals([
            'status' => 'success',
            'data'   => $row,
        ]);
    }

    public function testCreateUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $numOfRecords = $I->grabNumRecords(Unit::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
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

    public function testCreateUnitWithMissingRequiredFields(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $numOfRecords = $I->grabNumRecords(Unit::TABLE);

        $I->sendPOST($this->getBaseUrl(), []);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('code');
        verify($response['data']['code'])->contains(sprintf(ValidationMessage::REQUIRED, 'code'));
        verify($response['data'])->hasKey('description');
        verify($response['data']['description'])->contains(sprintf(ValidationMessage::REQUIRED, 'description'));
        $I->seeNumRecords($numOfRecords, Unit::TABLE);
    }

    public function testCreateUnitWithAlreadyExistCode(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $numOfRecords = $I->grabNumRecords(Unit::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'code'        => 'un1',
            'description' => 'duplicated',
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('code');
        verify($response['data']['code'])->contains(sprintf(ValidationMessage::UNIQUE, 'code'));
        $I->seeNumRecords($numOfRecords, Unit::TABLE);
    }

    public function testCreateUnitWithTooLong(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $numOfRecords = $I->grabNumRecords(Unit::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'code'        => 'way too long unit code',
            'description' => 'super long unit' . str_repeat('x', 40),
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('code');
        verify($response['data']['code'])->contains(sprintf(ValidationMessage::MAX_STRING, 'code', '3'));
        verify($response['data'])->hasKey('description');
        verify($response['data']['description'])->contains(sprintf(ValidationMessage::MAX_STRING, 'description', '40'));
        $I->seeNumRecords($numOfRecords, Unit::TABLE);
    }

    public function testUpdateUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $before = $I->grabRecord(Unit::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
            'code'        => 'cj1',
            'description' => 'updated',
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('success');
        $after = $I->grabRecord(Unit::TABLE, ['id' => 1]);
        verify($before)->notEquals($after);
        verify('Should be unable to update code.', $after['code'])->notEquals('cj1');
        verify('Should be able to update description.', $after['description'])->equals('updated');
        verify($response['data'])->equals($after);
    }

    public function testUpdateUnitWithTooLong(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $before = $I->grabRecord(Unit::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
            'description' => 'super long unit' . str_repeat('x', 40),
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['description' => 40] as $field => $max) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::MAX_STRING, str_replace('_', ' ', $field), $max));
        }
        $after = $I->grabRecord(Unit::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function testUpdateUnitWithMissingRequiredFields(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $before = $I->grabRecord(Unit::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", []);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['description'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::REQUIRED, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord(Unit::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function testDeleteUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $before = $I->grabRecord(Unit::TABLE, ['id' => 1]);

        $I->sendDELETE("{$this->getBaseUrl()}/1");

        $I->seeResponseCodeIs(200);
        $I->dontSeeInDatabase(Unit::TABLE, $before);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $before,
        ]);
    }
}
