<?php

namespace App\Database\Seeds;


use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WorkCenter;

class WorkCenterControllerSeeder extends Seeder
{
    public function run()
    {
        $this->db->table(Warehouse::TABLE)->delete();
        $this->db->table(Warehouse::TABLE)->insert([
            [
                'id'                         => 1,
                'code'                       => 'wh1',
                'description'                => 'wh1 description',
                'negative_inventory_allowed' => 1,
                'manual_adjustment_allowed'  => 1,
            ],
        ]);

        $this->db->table(Unit::TABLE)->delete();
        $this->db->table(Unit::TABLE)->insert([
            [
                'id'          => 1,
                'code'        => 'un1',
                'description' => 'un1 desc',
            ],
        ]);

        $this->db->table(WorkCenter::TABLE)->delete();
        $this->db->table(WorkCenter::TABLE)->insert([
            [
                'id'                      => 1,
                'code'                    => 'wc1',
                'description'             => 'work center 1',
                'type'                    => WorkCenter::TYPE_SUB_WORK_CENTER,
                'time_unit_id'            => 1,
                'shop_floor_warehouse_id' => 1,
                'parent_work_center_id'   => 2,
                'costing_work_center_id'  => 3,
            ],
            [
                'id'                      => 2,
                'code'                    => 'wc2',
                'description'             => 'work center 2',
                'type'                    => WorkCenter::TYPE_WORK_CENTER,
                'time_unit_id'            => 1,
                'shop_floor_warehouse_id' => 1,
                'parent_work_center_id'   => null,
                'costing_work_center_id'  => null,
            ],
            [
                'id'                      => 3,
                'code'                    => 'wc3',
                'description'             => 'work center 3',
                'type'                    => WorkCenter::TYPE_COST,
                'time_unit_id'            => 1,
                'shop_floor_warehouse_id' => null,
                'parent_work_center_id'   => null,
                'costing_work_center_id'  => null,
            ],
        ]);
    }
}
