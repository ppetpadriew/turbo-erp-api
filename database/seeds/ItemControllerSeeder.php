<?php

namespace App\Database\Seeds;


use App\Models\Item;
use App\Models\Unit;

class ItemControllerSeeder extends Seeder
{
    public function run()
    {
        $this->db->table(Unit::TABLE)->delete();
        $this->db->table(Unit::TABLE)->insert([
            ['id' => 1, 'code' => 'un1', 'description' => 'un1 desc'],
            ['id' => 2, 'code' => 'un2', 'description' => 'un2 desc'],
        ]);

        $this->db->table(Item::TABLE)->delete();
        $this->db->table(Item::TABLE)->insert([
            [
                'id'                => 1,
                'code'              => 'item-1',
                'ean'               => '1234567890123',
                'description'       => 'item-1 desc',
                'item_type'         => Item::ITEM_TYPE_PURCHASED,
                'inventory_unit_id' => 1,
                'weight'            => '0',
                'weight_unit_id'    => 1,
                'lot_controlled'    => 0,
                'created_datetime'  => '2018-01-01 00:00:00',
                'updated_datetime'  => '2018-01-01 00:00:00',
            ],
        ]);
    }
}
