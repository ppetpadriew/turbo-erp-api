<?php

namespace App\Tests\Api;


use ApiTester;
use App\Database\Seeds\ItemControllerSeeder;
use App\Models\Item;
use App\Tests\ValidationMessage;

class ItemCest extends BaseCest
{
    public function getBaseUrl(): string
    {
        return '/items';
    }

    public function createItemWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), []);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify(array_keys($response['data']))->equals([
            'code',
            'item_type_id',
            'inventory_unit_id',
            'weight',
            'weight_unit_id',
        ]);
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function createItemWithAlreadyExistCode(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'code' => 'item-1',
        ]);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        verify($response['data'])->hasKey('code');
        verify($response['data']['code'])->contains(sprintf(ValidationMessage::UNIQUE, 'code'));
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function createItemWithTooLong(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'code'        => str_repeat('super long', 100),
            'ean'         => str_repeat('super long', 100),
            'description' => str_repeat('super long', 100),
        ]);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['code' => 20, 'ean' => 13, 'description' => 100] as $field => $max) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::MAX_STRING, $field, $max));
        }
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function createItemWithNonExistenceReference(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'item_type_id'      => 99,
            'inventory_unit_id' => 99,
            'weight_unit_id'    => 99,
        ]);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['item_type_id', 'inventory_unit_id', 'weight_unit_id'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::EXIST, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function createItemWithInvalidFieldTypes(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'weight'         => 'some string',
            'lot_controlled' => 'some string',
        ]);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        $fields = ['weight' => ValidationMessage::NUMERIC, 'lot_controlled' => ValidationMessage::BOOLEAN];
        foreach ($fields as $field => $message) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf($message, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }
}
