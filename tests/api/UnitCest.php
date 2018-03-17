<?php

namespace App\Tests\Api;

use ApiTester;
use App\Database\Seeds\UnitControllerSeeder;
use App\Models\Unit;

class UnitCest extends BaseCest
{
    protected function getBaseUrl(): string
    {
        return '/units';
    }

    public function testListUnits(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testList($I, Unit::TABLE);
    }

    public function testGetUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testGet($I, Unit::TABLE, 1);
    }

    public function testCreateUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testCreate($I, Unit::TABLE, [
            'code'        => 'xxx',
            'description' => 'xxx desc',
        ]);
    }

    public function testCreateUnitWithMissingRequiredFields(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testCreateWithMissingRequiredFields($I, Unit::TABLE, ['code', 'description']);
    }

    public function testCreateUnitWithAlreadyExistCode(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testCreateWithAlreadyExist($I, Unit::TABLE, [
            'code' => 'un1',
        ]);
    }

    public function testCreateUnitWithTooLong(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testCreateWithTooLong($I, Unit::TABLE, ['code' => 3, 'description' => 40]);
    }

    public function testUpdateUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testUpdate($I, Unit::TABLE, ['description' => 'updated',], 1);
    }

    public function testUpdateUnitWithUnfillableFields(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testUpdateWithUnfillableFields($I, Unit::TABLE, ['code' => 'cj1'], 1);
    }

    public function testUpdateUnitWithTooLong(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testUpdateWithTooLong($I, Unit::TABLE, ['description' => 40], 1);
    }

    public function testUpdateUnitWithMissingRequiredFields(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testUpdateWithMissingRequiredFields($I, Unit::TABLE, ['description'], 1);
    }

    public function testDeleteUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $this->testDelete($I, Unit::TABLE, 1);
    }
}
