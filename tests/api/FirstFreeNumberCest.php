<?php

namespace App\Tests\Api;


use ApiTester;
use App\Database\Seeds\FirstFreeNumberControllerSeeder;
use App\Models\FirstFreeNumber;
use App\Tests\ValidationMessage;

class FirstFreeNumberCest extends BaseCest
{

    protected function getBaseUrl(): string
    {
        return 'first_free_numbers';
    }

    public function testListFirstFreeNumbers(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $I->seeNumRecords(2, FirstFreeNumber::TABLE);
    }

    public function testGetFirstFreeNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testGet($I, FirstFreeNumber::TABLE, 1);
    }

    public function testCreateFirstFreeNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $data = [
            'series' => 'DUMMY',
            'length' => 8,
        ];
        $this->testCreate($I, FirstFreeNumber::TABLE, $data);
    }

    public function testCreateFirstFreeNumberWithDefaultValues(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $data = [
            'series' => 'DUMMY',
        ];
        $this->testCreate($I, FirstFreeNumber::TABLE, $data, ['length' => 9, 'last_used_number' => 0]);
    }

    public function testCreateFirstFreeNumberWithMissingRequiredFields(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testCreateWithMissingRequiredFields($I, FirstFreeNumber::TABLE, ['series']);
    }

    public function testCreateFirstFreeNumberWithTooLong(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testCreateWithTooLong($I, FirstFreeNumber::TABLE, ['series' => 8]);
    }

    public function testCreateFirstFreeNumberWithInvalidFieldTypes(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $messages = ['first_free_number' => ValidationMessage::NUMERIC, 'length' => ValidationMessage::NUMERIC];
        $this->testCreateWithInvalidFieldTypes($I, FirstFreeNumber::TABLE, [
            'first_free_number' => 'some string',
            'length'            => 'some string',
        ], $messages);
    }

    public function testCreateFirstFreeNumberWithTooBigNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testCreateWithTooBigNumber($I, FirstFreeNumber::TABLE, ['length' => 9], 1);
    }

    public function testCreateFirstFreeNumberWithTooSmallNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testCreateWithTooSmallNumber($I, FirstFreeNumber::TABLE, ['length' => 1], 1);
    }

    public function testUpdateFirstFreeNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $updateData = [
            'series'      => 'UPD',
            'description' => 'updated',
            'length'      => 4,
        ];
        $this->testUpdate($I, FirstFreeNumber::TABLE, $updateData, 1);
    }

    public function testUpdateFirstFreeNumberWithMissingRequiredFields(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testUpdateWithMissingRequiredFields($I, FirstFreeNumber::TABLE, ['series'], 1);
    }

    public function testUpdateFirstFreeNumberWithUnFillableFields(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testUpdateWithUnfillableFields($I, FirstFreeNumber::TABLE, ['last_used_number' => 50], 1);
    }

    public function testUpdateFirstFreeNumberWithNullableFields(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testUpdateWithNullableFields($I, FirstFreeNumber::TABLE, ['description'], 1);
    }

    public function testUpdateFirstFreeNumberWithTooLong(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testUpdateWithTooLong($I, FirstFreeNumber::TABLE, ['series' => 8], 1);
    }

    public function testUpdateFirstFreeNumberWithTooBigNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testUpdateWithTooBigNumber($I, FirstFreeNumber::TABLE, ['length' => 9], 1);
    }

    public function testUpdateFirstFreeNumberWithTooSmallNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testUpdateWithTooSmallNumber($I, FirstFreeNumber::TABLE, ['length' => 1], 1);
    }

    public function testUpdateFirstFreeNumberWithInvalidFieldTypes(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $updateData = [
            'first_free_number' => 'some string',
            'length'            => 'some string',
        ];
        $fields = ['first_free_number' => ValidationMessage::NUMERIC, 'length' => ValidationMessage::NUMERIC];
        $this->testUpdateWithInvalidFieldTypes($I, FirstFreeNumber::TABLE, $updateData, $fields, 1);
    }

    public function testDeleteFirstFreeNumber(ApiTester $I)
    {
        (new FirstFreeNumberControllerSeeder)->run();
        $this->testDelete($I, FirstFreeNumber::TABLE, 1);
    }
}
