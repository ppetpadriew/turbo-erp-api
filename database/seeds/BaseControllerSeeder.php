<?php

namespace App\Database\Seeds;

use App\Models\Unit;

class BaseControllerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->db->table(Unit::TABLE)->delete();
        $this->db->table(Unit::TABLE)->insert([
            ['id' => 1, 'code' => 'un1', 'description' => 'un1 desc'],
            ['id' => 2, 'code' => 'un2', 'description' => 'un2 desc'],
        ]);
    }
}
