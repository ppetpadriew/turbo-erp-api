<?php

namespace App\Database\Seeds;


use App\Models\Address;
use App\Models\BusinessPartner;

class BusinessPartnerControllerSeeder extends Seeder
{
    public function run()
    {
        $this->db->table(Address::TABLE)->delete();
        $this->db->table(Address::TABLE)->insert([
            [
                'id'           => 1,
                'house_number' => '111/11',
            ],
            [
                'id'           => 2,
                'house_number' => '22/12',
            ],
        ]);

        $this->db->table(BusinessPartner::TABLE)->delete();
        $this->db->table(BusinessPartner::TABLE)->insert([
            [
                'id'         => 1,
                'title'      => 'Mr',
                'name'       => 'dummy',
                'language'   => 2,
                'address_id' => 1,
            ],
        ]);
    }
}
