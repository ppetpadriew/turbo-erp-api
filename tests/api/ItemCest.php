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

    public function testListItems(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testList($I, Item::TABLE);
    }

    public function testGetItem(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testGet($I, Item::TABLE, 1);
    }

    public function testCreateItem(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $data = [
            'code'              => 'valid-item',
            'ean'               => '1122334455123',
            'description'       => 'valid-item desc',
            'item_type_id'      => 1,
            'inventory_unit_id' => 1,
            'weight'            => 0,
            'weight_unit_id'    => 1,
            'lot_controlled'    => 1,
        ];
        $this->testCreate($I, Item::TABLE, $data, []);
    }

    public function testCreateItemWithDefaultValues(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $data = [
            'code'              => 'valid-item',
            'ean'               => '1122334455123',
            'description'       => 'valid-item desc',
            'item_type_id'      => 1,
            'inventory_unit_id' => 1,
            'weight'            => 0,
            'weight_unit_id'    => 1,
        ];
        $this->testCreate($I, Item::TABLE, $data, ['lot_controlled' => 0]);
    }

    public function testCreateItemWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->createWithMissingRequiredFields($I, Item::TABLE, [
            'code',
            'item_type_id',
            'inventory_unit_id',
            'weight',
            'weight_unit_id',
        ]);
    }

    public function testCreateItemWithAlreadyExistCode(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testCreateWithAlreadyExist($I, Item::TABLE, [
            'code' => 'item-1',
            'ean'  => '1234567890123',
        ]);
    }

    public function testCreateItemWithTooLong(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testCreateWithTooLong($I, Item::TABLE, ['code' => 20, 'ean' => 13, 'description' => 100]);
    }

    public function testCreateItemWithNonExistenceReference(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testCreateWithNonExistenceReference($I, Item::TABLE, ['item_type_id', 'inventory_unit_id', 'weight_unit_id']);
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
        $updateData = [
            'ean'               => '1234567890124',
            'description'       => 'item-1 updated',
            'item_type_id'      => 2,
            'inventory_unit_id' => 2,
            'weight'            => '20',
            'weight_unit_id'    => 2,
            'lot_controlled'    => 1,
        ];
        $this->testUpdate($I, Item::TABLE, $updateData, 1);
    }

    public function testUpdateItemWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testUpdateWithMissingRequiredFields(
            $I,
            Item::TABLE,
            ['item_type_id', 'inventory_unit_id', 'weight', 'weight_unit_id', 'lot_controlled'],
            1
        );
    }

    public function testUpdateItemWithUnFillableFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testUpdateWithUnfillableFields($I, Item::TABLE, ['code' => 'some string'], 1);
    }

    public function testUpdateItemWithoutChangingUniqueField(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testUpdateWithoutChangingUniqueFields($I, Item::TABLE, 1);
    }

    public function testUpdateItemWithNullableFields(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testUpdateWithNullableFields($I, Item::TABLE, ['ean', 'description'], 1);
    }

    public function testUpdateItemWithTooLong(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testUpdateWithTooLong($I, Item::TABLE, ['ean' => 13, 'description' => 100], 1);
    }

    public function testUpdateItemWithNonExistenceReference(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testUpdateWithNonExistenceReference($I, Item::TABLE, ['item_type_id', 'inventory_unit_id', 'weight_unit_id'], 1);
    }

    public function testUpdateItemWithInvalidFieldTypes(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $updateData = [
            'weight'         => 'some string',
            'lot_controlled' => 'some string',
        ];
        $fields = ['weight' => ValidationMessage::NUMERIC, 'lot_controlled' => ValidationMessage::BOOLEAN];
        $this->testUpdateWithInvalidFieldTypes($I, Item::TABLE, $updateData, $fields, 1);
    }

    public function testDeleteItem(ApiTester $I)
    {
        (new ItemControllerSeeder)->run();
        $this->testDelete($I, Item::TABLE, 1);
    }
}
