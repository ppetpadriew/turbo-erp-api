<?php

namespace App\Tests\Api;


use ApiTester;
use App\Database\Seeds\ItemControllerSeeder;
use App\Models\Item;
use App\Tests\ValidationMessage;

class ItemCest extends BaseCest
{
    protected function getBaseUrl(): string
    {
        return '/items';
    }

    public function testGetItems(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();

        $I->sendGET($this->getBaseUrl());
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => [
                [
                    'id'                => 1,
                    'code'              => 'item-1',
                    'ean'               => '1234567890123',
                    'description'       => 'item-1 desc',
                    'item_type_id'      => 1,
                    'inventory_unit_id' => 1,
                    'weight'            => '0',
                    'weight_unit_id'    => 1,
                    'lot_controlled'    => 0,
                    'created_datetime'  => '2018-01-01 00:00:00',
                    'updated_datetime'  => '2018-01-01 00:00:00',
                ],
            ],
        ]);
    }

    public function testGetItem(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $row = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendGET("{$this->getBaseUrl()}/1");
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $row,
        ]);
    }

    public function testCreateItem(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);
        $data = [
            'code'              => 'valid-item',
            'ean'               => '1122334455123',
            'description'       => 'valid-item desc',
            'item_type_id'      => 1,
            'inventory_unit_id' => 1,
            'weight'            => '0',
            'weight_unit_id'    => 1,
            'lot_controlled'    => 0,
        ];

        $I->sendPOST($this->getBaseUrl(), $data);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $data,
        ]);
        $I->seeNumRecords($numOfRecord + 1, Item::TABLE);
    }

    public function testCreateItemWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), []);

        $I->seeResponseCodeIs(400);
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

    public function testCreateItemWithAlreadyExistCode(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'code' => 'item-1',
            'ean'  => '1234567890123',
        ]);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['code', 'ean'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::UNIQUE, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function tesstCreateItemWithTooLong(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'code'        => str_repeat('super long', 100),
            'ean'         => str_repeat('super long', 100),
            'description' => str_repeat('super long', 100),
        ]);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['code' => 20, 'ean' => 13, 'description' => 100] as $field => $max) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::MAX_STRING, $field, $max));
        }
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function testCreateItemWithNonExistenceReference(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'item_type_id'      => 99,
            'inventory_unit_id' => 99,
            'weight_unit_id'    => 99,
        ]);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['item_type_id', 'inventory_unit_id', 'weight_unit_id'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::EXIST, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function testCreateItemWithInvalidFieldTypes(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $numOfRecord = $I->grabNumRecords(Item::TABLE);

        $I->sendPOST($this->getBaseUrl(), [
            'weight'         => 'some string',
            'lot_controlled' => 'some string',
        ]);

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        $fields = ['weight' => ValidationMessage::NUMERIC, 'lot_controlled' => ValidationMessage::BOOLEAN];
        foreach ($fields as $field => $message) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf($message, str_replace('_', ' ', $field)));
        }
        $I->seeNumRecords($numOfRecord, Item::TABLE);
    }

    public function testUpdateItem(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'ean'               => '1234567890124',
                'description'       => 'item-1 updated',
                'item_type_id'      => 2,
                'inventory_unit_id' => 2,
                'weight'            => '20',
                'weight_unit_id'    => 2,
                'lot_controlled'    => 1,
            ]
        );

        $I->seeResponseCodeIs(200);
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $after,
        ]);
        verify($before)->notEquals($after);
        verify($after['ean'])->equals('1234567890124');
        verify($after['description'])->equals('item-1 updated');
        verify($after['item_type_id'])->equals(2);
        verify($after['inventory_unit_id'])->equals(2);
        verify($after['weight'])->equals(20);
        verify($after['weight_unit_id'])->equals(2);
        verify($after['lot_controlled'])->equals(1);
    }

    public function testUpdateItemWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testUpdateMissingRequiredFields(
            $I,
            Item::TABLE,
            "{$this->getBaseUrl()}/1",
            ['item_type_id', 'inventory_unit_id', 'weight', 'weight_unit_id', 'lot_controlled'],
            1
        );
    }

    public function testUpdateItemWithUnFillableFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'code' => 'some string',
            ] + $before
        );

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data'   => $before,
        ]);
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function testUpdateItemWithoutChangingUniqueField(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", $before);

        $I->seeResponseCodeIs(200);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('success');
    }

    public function testUpdateNullableFieldsWithNull(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'ean'         => null,
                'description' => null,
            ] + $before
        );

        $I->seeResponseCodeIs(200);
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

    public function testUpdateItemWithTooLong(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'ean'         => str_repeat('super long', 100),
                'description' => str_repeat('super long', 100),
            ] + $before
        );

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['ean' => 13, 'description' => 100] as $field => $max) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::MAX_STRING, $field, $max));
        }
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function testUpdateItemWithNonExistenceReference(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'item_type_id'      => 99,
                'inventory_unit_id' => 99,
                'weight_unit_id'    => 99,
            ] + $before
        );

        $I->seeResponseCodeIs(400);
        $response = $I->grabJsonResponse();
        verify($response['status'])->equals('fail');
        foreach (['item_type_id', 'inventory_unit_id', 'weight_unit_id'] as $field) {
            verify($response['data'])->hasKey($field);
            verify($response['data'][$field])->contains(sprintf(ValidationMessage::EXIST, str_replace('_', ' ', $field)));
        }
        $after = $I->grabRecord(Item::TABLE, ['id' => 1]);
        verify($before)->equals($after);
    }

    public function testUpdateItemWithInvalidFieldTypes(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $before = $I->grabRecord(Item::TABLE, ['id' => 1]);

        $I->sendPUT("{$this->getBaseUrl()}/1", [
                'weight'         => 'some string',
                'lot_controlled' => 'some string',
            ] + $before
        );

        $I->seeResponseCodeIs(400);
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

    public function testDeleteItem(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testDelete($I, Item::TABLE, "{$this->getBaseUrl()}/1", 1);
    }
}
