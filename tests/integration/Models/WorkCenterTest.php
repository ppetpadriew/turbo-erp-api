<?php

namespace App\Tests\Integration\Models;


use App\Database\Seeds\WorkCenterControllerSeeder;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WorkCenter;
use App\Tests\Integration\Integration;

class WorkCenterTest extends Integration
{
    public function _before()
    {
        parent::_before();
        (new WorkCenterControllerSeeder)->run();
    }

    public function testTimeUnit()
    {
        /** @var WorkCenter $workCenterModel */
        $workCenterModel = WorkCenter::find(1);

        $timeUnitModel = $workCenterModel->time_unit;

        verify($timeUnitModel)->isInstanceOf(Unit::class);
        verify($timeUnitModel->id)->equals(1);
    }

    public function testShopFloorWarehouse()
    {
        /** @var WorkCenter $workCenterModel */
        $workCenterModel = WorkCenter::find(1);

        $shopFloorWarehouseModel = $workCenterModel->shop_floor_warehouse;

        verify($shopFloorWarehouseModel)->isInstanceOf(Warehouse::class);
        verify($shopFloorWarehouseModel->id)->equals(1);
    }

    public function testParentWorkCenter()
    {
        /** @var WorkCenter $workCenterModel */
        $workCenterModel = WorkCenter::find(1);

        $parentWorkCenterModel = $workCenterModel->parent_work_center;

        verify($parentWorkCenterModel)->isInstanceOf(WorkCenter::class);
        verify($parentWorkCenterModel->id)->equals(2);
    }

    public function testCostingWorkCenter()
    {
        /** @var WorkCenter $workCenterModel */
        $workCenterModel = WorkCenter::find(1);

        $costingWorkCenterModel = $workCenterModel->costing_work_center;

        verify($costingWorkCenterModel)->isInstanceOf(WorkCenter::class);
        verify($costingWorkCenterModel->id)->equals(3);
    }
}
