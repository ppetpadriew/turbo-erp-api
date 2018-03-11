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
            'ean'  => '1234567890123',
        ]);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['code', 'ean'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::UNIQUE, str_replace('_', ' ', $field)));
        }
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

    public function updateItemWithUnFillableFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'code' => 'some string',
            ] + $before
        );

        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function updateItemWithoutChangingUniqueField(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", $before);

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('success');
    }

    public function updateNullableFieldsWithNull(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'ean'         => null,
                'description' => null,
            ] + $before
        );

        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => [
                'ean'         => null,
                'description' => null,
            ],
        ]);
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->notEquals($after);
        verify($after['ean'])->null();
        verify($after['description'])->null();
    }

    public function updateItemWithTooLong(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'ean'         => str_repeat('super long', 100),
                'description' => str_repeat('super long', 100),
            ] + $before
        );

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['ean' => 13, 'description' => 100] as $field => $max) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::MAX_STRING, $field, $max));
        }
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function updateItemWithNonExistenceReference(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'item_type_id'      => 99,
                'inventory_unit_id' => 99,
                'weight_unit_id'    => 99,
            ] + $before
        );

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['item_type_id', 'inventory_unit_id', 'weight_unit_id'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::EXIST, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function updateItemWithInvalidFieldTypes(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'weight'         => 'some string',
                'lot_controlled' => 'some string',
            ] + $before
        );

        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        $fields = ['weight' => ValidationMessage::NUMERIC, 'lot_controlled' => ValidationMessage::BOOLEAN];
        foreach ($fields as $field => $message) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf($message, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }
}
