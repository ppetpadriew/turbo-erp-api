<?php

namespace App\Tests\Api;

use ApiTester;
use App\Database\Seeds\UnitControllerSeeder;

/**
 * Class BaseControllerCest
 *
 * Test the generic things on base controller which apply to all controllers
 */
class BaseControllerCest extends BaseCest
{
    protected function getBaseUrl(): string
    {
        return '/units';
    }

    public function testDeleteNonExistentUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendDELETE("{$this->getBaseUrl()}/99");
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'error',
        ]);
    }

    public function testUpdateNonExistenceResource(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendPUT("{$this->getBaseUrl()}/99", [
            'description' => 'updated',
        ]);
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'error',
        ]);
    }
}
