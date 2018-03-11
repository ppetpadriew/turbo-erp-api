<?php

namespace App\Database\Seeds;


use App\Models\ItemType;

class ItemTypeControllerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->db->table(ItemType::TABLE)->delete();
        $this->db->table(ItemType::TABLE)->insert([
            ['id' => 1, 'description' => 'type 1'],
            ['id' => 2, 'description' => 'type 2'],
        ]);
    }
}
