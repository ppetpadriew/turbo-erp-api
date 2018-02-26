<?php

namespace App\Database\Seeds;


use App\Models\Unit;

class UnitControllerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // If auto commit is set then we don't need to manually do it
        // $this->db->getPdo()->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        $this->db->table(Unit::TABLE)->delete();
        $this->db->table(Unit::TABLE)->insert([
            ['id' => 1, 'code' => 'un1', 'description' => 'un1 desc'],
            ['id' => 2, 'code' => 'un2', 'description' => 'un2 desc'],
        ]);
        // May need to commit if you wanna see in the db
//        $this->db->getPdo()->commit();
    }
}
