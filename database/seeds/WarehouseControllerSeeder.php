<?php

namespace App\Database\Seeds;


use App\Models\Warehouse;

class WarehouseControllerSeeder extends Seeder
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
    }
}
