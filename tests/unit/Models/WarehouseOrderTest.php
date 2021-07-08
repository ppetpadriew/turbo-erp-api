<?php

namespace App\Tests\Unit\Models;


use App\Models\WarehouseOrder;
use App\Tests\Unit\Unit;

class WarehouseOrderTest extends Unit
{
    public function testIsSalesType()
    {
        $this->specify('It should return true when order origin is either "Sales" or "Sales (Manual)". Otherwise return false.',
            function ($orderOrigin, $expected) {
                $warehouseOrderModel = new WarehouseOrder;
                $warehouseOrderModel->order_origin = $orderOrigin;

                $result = $warehouseOrderModel->isSalesType();

                verify($result)->equals($expected);
            }, [
                'examples' => [
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES_MANUAL, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER_MANUAL, 'expected' => false],
                ],
            ]);
    }

    public function testIsPurchaseType()
    {
        $this->specify('It should return true when order origin is either "Purchase" or "Purchase (Manual)". Otherwise return false.',
            function ($orderOrigin, $expected) {
                $warehouseOrderModel = new WarehouseOrder;
                $warehouseOrderModel->order_origin = $orderOrigin;

                $result = $warehouseOrderModel->isPurchaseType();

                verify($result)->equals($expected);
            }, [
                'examples' => [
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE_MANUAL, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER_MANUAL, 'expected' => false],
                ],
            ]);
    }

    public function testIsProductionType()
    {
        $this->specify('It should return true when order origin is either "Production" or "Production (Manual)". Otherwise return false.',
            function ($orderOrigin, $expected) {
                $warehouseOrderModel = new WarehouseOrder;
                $warehouseOrderModel->order_origin = $orderOrigin;

                $result = $warehouseOrderModel->isProductionType();

                verify($result)->equals($expected);
            }, [
                'examples' => [
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION_MANUAL, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER_MANUAL, 'expected' => false],
                ],
            ]);
    }

    public function testIsTransferType()
    {
        $this->specify('It should return true when order origin is either "Transfer" or "Transfer (Manual)". Otherwise return false.',
            function ($orderOrigin, $expected) {
                $warehouseOrderModel = new WarehouseOrder;
                $warehouseOrderModel->order_origin = $orderOrigin;

                $result = $warehouseOrderModel->isTransferType();

                verify($result)->equals($expected);
            }, [
                'examples' => [
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_TRANSFER_MANUAL, 'expected' => true],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PRODUCTION_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_PURCHASE_MANUAL, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES, 'expected' => false],
                    ['orderOrigin' => WarehouseOrder::ORDER_ORIGIN_SALES_MANUAL, 'expected' => false],
                ],
            ]);
    }
}
