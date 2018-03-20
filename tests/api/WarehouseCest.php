<?php

namespace App\Tests\Api;


use ApiTester;
use App\Database\Seeds\WarehouseControllerSeeder;
use App\Models\Warehouse;
use App\Tests\ValidationMessage;

class WarehouseCest extends BaseCest
{
    protected function getBaseUrl(): string
    {
        return '/warehouses';
    }

    public function testCreateWarehouse(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testCreate($I, Warehouse::TABLE, [
            'code'                       => 'whr 99',
            'description'                => '99 desc',
            'negative_inventory_allowed' => 1,
            'manual_adjustment_allowed'  => 1,
        ]);
    }

    public function testCreateWarehouseWithDefaultValues(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testCreate($I, Warehouse::TABLE, [
            'code'        => 'whr 99',
            'description' => '99 desc',
        ], [
            'negative_inventory_allowed' => 0,
            'manual_adjustment_allowed'  => 1,
        ]);
    }

    public function testCreateWarehouseWithNullableFields(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testCreateWithNullableFields($I, Warehouse::TABLE, [
            'code'                       => 'whr 99',
            'negative_inventory_allowed' => 1,
            'manual_adjustment_allowed'  => 1,
        ], [
            'description',
        ]);
    }

    public function testCreateWarehouseWithMissingRequiredFields(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testCreateWithMissingRequiredFields($I, Warehouse::TABLE, ['code']);
    }

    public function testCreateWarehouseWithInvalidFieldTypes(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testCreateWithInvalidFieldTypes($I, Warehouse::TABLE, [
            'negative_inventory_allowed' => 'some string',
            'manual_adjustment_allowed'  => 'some string',
        ], [
            'negative_inventory_allowed' => ValidationMessage::BOOLEAN,
            'manual_adjustment_allowed'  => ValidationMessage::BOOLEAN,
        ]);
    }

    public function testCreateWarehouseWithTooLong(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testCreateWithTooLong($I, Warehouse::TABLE, [
            'code'        => 6,
            'description' => 30,
        ]);
    }

    public function testUpdateWarehouse(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testUpdate($I, Warehouse::TABLE, [
            'description'                => 'updated',
            'negative_inventory_allowed' => 0,
            'manual_adjustment_allowed'  => 0,
        ], 1);
    }

    public function testUpdateWarehouseWithTooLong(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testUpdate($I, Warehouse::TABLE, [
            'description' => 30,
        ], 1);
    }

    public function testUpdateWarehouseWithUnfillableFields(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testUpdateWithUnfillableFields($I, Warehouse::TABLE, [
            'code' => 'updated',
        ], 1);
    }

    public function testUpdateWarehouseWithInvalidFieldTypes(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testUpdateWithInvalidFieldTypes($I, Warehouse::TABLE, [
            'negative_inventory_allowed' => 'some string',
            'manual_adjustment_allowed'  => 'some string',
        ], [
            'negative_inventory_allowed' => ValidationMessage::BOOLEAN,
            'manual_adjustment_allowed'  => ValidationMessage::BOOLEAN,
        ], 1);
    }

    public function testUpdateWarehouseWithMissingRequiredFields(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testUpdateWithMissingRequiredFields($I, Warehouse::TABLE, [
            'negative_inventory_allowed',
            'manual_adjustment_allowed',
        ], 1);
    }

    public function testUpdateWarehouseWithNullableFields(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testUpdateWithNullableFields($I, Warehouse::TABLE, ['description'], 1);
    }

    public function testDeleteWarehouse(ApiTester $I)
    {
        (new WarehouseControllerSeeder)->run();
        $this->testDelete($I, Warehouse::TABLE, 1);
    }
}
