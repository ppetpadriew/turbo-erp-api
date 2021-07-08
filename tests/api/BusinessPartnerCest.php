<?php

namespace App\Tests\Api;


use ApiTester;
use App\Database\Seeds\BusinessPartnerControllerSeeder;
use App\Models\BusinessPartner;

class BusinessPartnerCest extends BaseCest
{

    protected function getBaseUrl(): string
    {
        return '/business_partners';
    }

    public function testListBusinessPartners(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testList($I, BusinessPartner::TABLE);
    }

    public function testGetBusinessPartner(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testGet($I, BusinessPartner::TABLE, 1);
    }

    public function testCreateBusinessPartner(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $data = [
            'title'      => 'Mr',
            'name'       => 'test',
            'language'   => 'en',
            'address_id' => 1,
        ];
        $this->testCreate($I, BusinessPartner::TABLE, $data);
    }

    public function testCreateBusinessPartnerWithMissingRequiredFields(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testCreateWithMissingRequiredFields($I, BusinessPartner::TABLE, [
            'title',
            'name',
            'language',
            'address_id',
        ]);
    }

    public function testCreateBusinessPartnerWithAlreadyExist(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testCreateWithAlreadyExist($I, BusinessPartner::TABLE, [
            'name' => 'dummy',
        ]);
    }

    public function testCreateBusinessPartnerWithTooLong(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testCreateWithTooLong($I, BusinessPartner::TABLE, [
            'title'    => 3,
            'name'     => 35,
            'language' => 3,
        ]);
    }

    public function testCreateBusinessPartnerWithNonExistenceReference(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testCreateWithNonExistenceReference($I, BusinessPartner::TABLE, ['address_id']);
    }

    public function testUpdateBusinessPartner(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $updateData = [
            'title'      => 'Ms',
            'name'       => 'updated',
            'language'   => 'th',
            'address_id' => 2,
        ];
        $this->testUpdate($I, BusinessPartner::TABLE, $updateData, 1);
    }

    public function testUpdateBusinessPartnerWithMissingRequiredFields(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testUpdateWithMissingRequiredFields(
            $I,
            BusinessPartner::TABLE,
            ['title', 'name', 'language', 'address_id'],
            1
        );
    }

    public function testUpdateBusinessPartnerWithoutChangingUniqueFields(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testUpdateWithoutChangingUniqueFields($I, BusinessPartner::TABLE, 1);
    }

    public function testUpdateBusinessPartnerWithTooLong(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testUpdateWithTooLong($I, BusinessPartner::TABLE, [
            'title'    => 3,
            'name'     => 35,
            'language' => 3,
        ], 1);
    }

    public function testUpdateBusinessPartnerWithNonExistenceReference(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testUpdateWithNonExistenceReference($I, BusinessPartner::TABLE, ['address_id'], 1);
    }

    public function testDeleteItem(ApiTester $I)
    {
        (new BusinessPartnerControllerSeeder)->run();
        $this->testDelete($I, BusinessPartner::TABLE, 1);
    }
}
