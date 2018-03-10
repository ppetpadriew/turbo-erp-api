<?php

use App\Database\Seeds\ItemTypeControllerSeeder;
use App\Models\ItemType;

class ItemTypeCest
{
    private $baseUrl = '/item_types';

    public function createItemTypeWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(ItemType::TABLE);

        $I->sendPOST($this->baseUrl, [
            'nonexistence field' => 'foo',
        ]);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('description');
        $I->seeNumRecords($numOfRecord, ItemType::TABLE);
    }

    public function createItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(ItemType::TABLE);

        $I->sendPOST($this->baseUrl, [
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

    public function createItemTypeWithAlreadyExistType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(ItemType::TABLE);

        $I->sendPOST($this->baseUrl, [
            'description' => 'type 1',
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('description');
        $I->seeNumRecords($numOfRecord, ItemType::TABLE);
    }

    public function updateItemTypeWithAlreadyExistType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();

        $I->sendPUT("{$this->baseUrl}/1", [
            'description' => 'type 2',
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('description');
        $I->seeInDatabase(ItemType::TABLE, [
            'id'          => 1,
            'description' => 'type 1',
        ]);
    }

    public function updateItemTypeWithoutChangingUniqueField(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();

        $I->sendPUT("{$this->baseUrl}/1", [
            'description' => 'type 1', // Client didn't change this but change other fields
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => [
                'description' => 'type 1',
            ],
        ]);
    }
}
