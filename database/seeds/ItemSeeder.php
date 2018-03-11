<?php

namespace App\Database\Seeds;


use App\Models\Item;
use App\Models\ItemType;

class ItemSeeder extends Seeder
{
    public function testItemType()
    {
        $this->db->table(ItemType::TABLE)->delete();
        $this->db->table(ItemType::TABLE)->insert([
            [
                'id'          => 1,
                'description' => 'Purchased',
            ],
        ]);

        $this->db->table(Item::TABLE)->delete();
        $this->db->table(Item::TABLE)->insert([
            [
                'id'                => 1,
                'code'              => 'item-1',
                'description'       => 'item-1 desc',
                'item_type_id'      => 1,
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
