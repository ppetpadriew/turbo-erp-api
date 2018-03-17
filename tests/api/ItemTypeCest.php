<?php

namespace App\Tests\Api;

use ApiTester;
use App\Database\Seeds\ItemTypeControllerSeeder;
use App\Models\ItemType;

class ItemTypeCest extends BaseCest
{
    protected function getBaseUrl(): string
    {
        return '/item_types';
    }

    public function testListItemTypes(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testList($I, ItemType::TABLE);
    }

    public function testGetItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testGet($I, ItemType::TABLE, 1);
    }

    public function testCreateItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testCreate($I, ItemType::TABLE, [
            'description' => 'type 99',
        ]);
    }

    public function testCreateItemTypeWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testCreateWithMissingRequiredFields($I, ItemType::TABLE, ['description']);
    }

    public function testCreateItemTypeWithAlreadyExistType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testCreateWithAlreadyExist($I, ItemType::TABLE, [
            'description' => 'type 1',
        ]);
    }

    public function testUpdateItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testUpdate($I, ItemType::TABLE, ['description' => 'type 99'], 1);
    }

    public function testUpdateItemTypeWithMissingRequiredFields(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testUpdateWithMissingRequiredFields($I, ItemType::TABLE, ['description'], 1);
    }

    public function testUpdateItemTypeWithAlreadyExistType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testUpdateWithAlreadyExist($I, ItemType::TABLE, [
            'description' => 'type 2',
        ], 1);
    }

    public function testUpdateItemTypeWithoutChangingUniqueField(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testUpdateWithoutChangingUniqueFields($I, ItemType::TABLE, 1);
    }

    public function testUpdateItemTypeWithTooLong(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testUpdateWithTooLong($I, ItemType::TABLE, ['description' => 30], 1);
    }

    public function testDeleteItemType(ApiTester $I)
    {
        (new ItemTypeControllerSeeder)->run();
        $this->testDelete($I, ItemType::TABLE, 1);
    }
}
