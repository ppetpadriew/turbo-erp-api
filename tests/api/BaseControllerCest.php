<?php

use App\Database\Seeds\UnitControllerSeeder;

/**
 * Class BaseControllerCest
 *
 * Test the generic things on base controller which apply to all controllers
 */
class BaseControllerCest
{
    private $baseUrl = '/units';

    public function deleteNonExistentUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendDELETE("{$this->baseUrl}/99");
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'error',
        ]);
    }

    public function updateNonExistentUnit(ApiTester $I)
    {
        (new UnitControllerSeeder)->run();
        $I->sendPUT("{$this->baseUrl}/99", [
            'description' => 'updated',
        ]);
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'error',
        ]);
    }
}
