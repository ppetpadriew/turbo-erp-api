<?php

namespace App\Database\Seeds;


use App\Models\FirstFreeNumber;

class FirstFreeNumberControllerSeeder extends Seeder
{
    public function run()
    {
        $this->db->table(FirstFreeNumber::TABLE)->delete();
        $this->db->table(FirstFreeNumber::TABLE)->insert([
            [
                'id'               => 1,
                'series'           => 'ORD',
                'description'      => 'General Order',
                'length'           => 8,
                'last_used_number' => 0,
            ],
            [
                'id'               => 2,
                'series'           => 'WHR',
                'description'      => 'Warehouse order',
                'length'           => 10,
                'last_used_number' => 0,
            ],
        ]);
    }
}
