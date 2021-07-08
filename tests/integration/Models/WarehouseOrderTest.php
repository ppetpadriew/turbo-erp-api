<?php

namespace App\Tests\Integration\Models;


use App\Database\Seeds\WarehouseOrderControllerSeeder;
use App\Models\Address;
use App\Models\BusinessPartner;
use App\Models\Warehouse;
use App\Models\WarehouseOrder;
use App\Models\WorkCenter;
use App\Tests\Integration\Integration;

class WarehouseOrderTest extends Integration
{
    public function testShipFromBusinessPartner()
    {
        $this->specify('It should return an instance of business partner.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(2);

            /** @var BusinessPartner $shipFromBPModel */
            $shipFromBPModel = $warehouseOrderModel->ship_from_business_partner;

            verify($shipFromBPModel)->isInstanceOf(BusinessPartner::class);
            verify($shipFromBPModel->id)->equals(2);
        });
    }

    public function testShipFromWarehouse()
    {
        $this->specify('It should return an instance of warehouse.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(1);

            /** @var Warehouse $shipFromWarehouseModel */
            $shipFromWarehouseModel = $warehouseOrderModel->ship_from_warehouse;

            verify($shipFromWarehouseModel)->isInstanceOf(Warehouse::class);
            verify($shipFromWarehouseModel->id)->equals(1);
        });
    }

    public function testShipFromWorkCenter()
    {
        $this->specify('It should return an instance of work center.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(3);

            /** @var WorkCenter $shipFromWorkCenterModel */
            $shipFromWorkCenterModel = $warehouseOrderModel->ship_from_work_center;

            verify($shipFromWorkCenterModel)->isInstanceOf(Warehouse::class);
            verify($shipFromWorkCenterModel->id)->equals(1);
        });
    }

    public function testShipFromAddress()
    {
        $this->specify('It should return an instance of address.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(1);

            /** @var Address $shipFromAddressModel */
            $shipFromAddressModel = $warehouseOrderModel->ship_from_address;

            verify($shipFromAddressModel)->isInstanceOf(Address::class);
            verify($shipFromAddressModel->id)->equals(1);
        });
    }

    public function testShipToBusinessPartner()
    {
        $this->specify('It should return an instance of business partner.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(1);

            /** @var BusinessPartner $shipToBPModel */
            $shipToBPModel = $warehouseOrderModel->ship_to_business_partner;

            verify($shipToBPModel)->isInstanceOf(BusinessPartner::class);
            verify($shipToBPModel->id)->equals(1);
        });
    }

    public function testShipToWarehouse()
    {
        $this->specify('It should return an instance of warehouse.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(3);

            /** @var Warehouse $shipToWarehouseModel */
            $shipToWarehouseModel = $warehouseOrderModel->ship_to_warehouse;

            verify($shipToWarehouseModel)->isInstanceOf(Warehouse::class);
            verify($shipToWarehouseModel->id)->equals(1);
        });
    }

    public function testShipToWorkCenter()
    {
        $this->specify('It should return an instance of work center.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(4);

            /** @var WorkCenter $shipToWorkCenter */
            $shipToWorkCenterModel = $warehouseOrderModel->ship_to_warehouse;

            verify($shipToWorkCenterModel)->isInstanceOf(WorkCenter::class);
            verify($shipToWorkCenterModel->id)->equals(1);
        });
    }

    public function testShipToAddress()
    {
        $this->specify('It should return an instance of address.', function () {
            (new WarehouseOrderControllerSeeder)->run();
            /** @var WarehouseOrder $warehouseOrderModel */
            $warehouseOrderModel = WarehouseOrder::find(1);

            /** @var Address $shipToAddressModel */
            $shipToAddressModel = $warehouseOrderModel->ship_to_warehouse;

            verify($shipToAddressModel)->isInstanceOf(Address::class);
            verify($shipToAddressModel->id)->equals(1);
        });
    }
}
