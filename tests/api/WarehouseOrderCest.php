<?php

namespace App\Tests\Api;


use ApiTester;
use App\Database\Seeds\WarehouseOrderControllerSeeder;
use App\Models\WarehouseOrder;

class WarehouseOrderCest extends BaseCest
{

    protected function getBaseUrl(): string
    {
        return '/warehouse_orders';
    }

    // @todo: implement other common tests

    public function testCreateSalesTypeIssueWarehouseOrder(ApiTester $I)
    {
        $numOfRecord = $I->grabNumRecords(WarehouseOrder::TABLE);
        (new WarehouseOrderControllerSeeder)->run();

        $I->sendPOST($this->getBaseUrl(), [
            'order_origin'         => WarehouseOrder::ORDER_ORIGIN_SALES,
            'transaction_type'     => WarehouseOrder::TRANSACTION_TYPE_ISSUE,
            'first_free_number_id' => 1,
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'status' => 'fail',
            'data'   => [
                'ship_from_warehouse_id'      => '',
                'ship_to_business_partner_id' => '',
            ],
        ]);
        $I->seeNumRecords($numOfRecord, WarehouseOrder::TABLE);
    }

    // @todo: testCreateSalesTypeReceipt

    public function testCreatePurchaseTypeWarehouseOrder()
    {
        // @todo: Test validation rules for this type
        verify(false)->true();
    }

    public function testCreateTransferTypeWarehouseOrder()
    {
        // @todo: Test validation rules for this type
        verify(false)->true();
    }

    public function testCreateProductionTypeWarehouseOrder()
    {
        // @todo: Test validation rules for this type
        verify(false)->true();
    }
}
