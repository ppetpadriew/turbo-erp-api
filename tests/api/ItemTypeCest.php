<?php

namespace App\Tests\Api;

use ApiTester;
use App\Database\Seeds\ItemTypeControllerSeeder;
use App\Models\ItemType;
use App\Tests\ValidationMessage;

class ItemTypeCest extends BaseCest
{
    protected function getBaseUrl(): string
    {
        return '/item_types';
    }

    public function testGetItemTypes(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();

        $I->sendGET($this->getBaseUrl());

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'description' => 'type 1'],
                ['id' => 2, 'description' => 'type 2'],
            ],
        ]);
    }

    public function testGetItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $row = $I->grabRecord(ItemType::TABLE, ['id' => 1]);

        $I->sendGET("{$this->getBaseUrl()}/1");

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $row,
        ]);
    }

    public function testCreateItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(ItemType::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'description' => 'type 99',
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => [
                'description' => 'type 99',
            ],
        ]);
        $I->seeNumRecords($numOfRecord + 1, ItemType::TABLE);
    }

    public function testCreateItemTypeWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(ItemType::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'nonexistence field' => 'foo',
        ]);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('description');
        $I->seeNumRecords($numOfRecord, ItemType::TABLE);
    }

    public function testCreateItemTypeWithAlreadyExistType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(ItemType::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'description' => 'type 1',
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('description');
        $I->seeNumRecords($numOfRecord, ItemType::TABLE);
    }

    public function testUpdateItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $before = $I->grabRecord(ItemType::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'description' => 'type 99',
            ] + $before
        );

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('success');
        $after = $I->grabRecord(ItemType::TABLE, ['id' => 1]);
        verify($before)->notEquals($after);
        verify($after['description'])->equals('type 99');
        verify($response['data'])->equals($after);
    }

    public function testUpdateItemTypeWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $before = $I->grabRecord(ItemType::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", []);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['description'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::REQUIRED, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord(ItemType::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function testUpdateItemTypeWithAlreadyExistType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $before = $I->grabRecord(ItemType::TABLE, ['id' => 1,]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'description' => 'type 2',
            ] + $before
        );

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['description'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::UNIQUE, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord(ItemType::TABLE, ['id' => 1,]);
        verify($before)->equals($after);
    }

    public function testUpdateItemTypeWithoutChangingUniqueField(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $before = $I->grabRecord(ItemType::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", $before);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $before,
        ]);
    }

    public function testDeleteItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $before = $I->grabRecord(ItemType::TABLE, ['id' => 1]);

        $I->sendDELETE("{$this->getBaseUrl()}/1");

        $I->seeResponseCodeIs(200);
        $I->dontSeeInDatabase(ItemType::TABLE, $before);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $before,
        ]);
    }
}
