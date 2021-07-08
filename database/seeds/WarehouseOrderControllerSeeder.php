<?php

namespace App\Database\Seeds;


use App\Models\Address;
use App\Models\BusinessPartner;
use App\Models\FirstFreeNumber;
use App\Models\Warehouse;
use App\Models\WarehouseOrder;

class WarehouseOrderControllerSeeder extends Seeder
{
    public function run()
    {
        $this->db->table(FirstFreeNumber::TABLE)->delete();
        $this->db->table(FirstFreeNumber::TABLE)->insert([
            [
                'id'                => 1,
                'number_group_id'   => 1,
                'series'            => 'FF',
                'description'       => 'first free 1',
                'first_free_number' => 1,
            ],
        ]);

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

        $this->db->table(Warehouse::TABLE)->delete();
        $this->db->table(Warehouse::TABLE)->insert([
            [
                'id'   => 1,
                'code' => 'wh1',
            ],
        ]);

        $this->db->table(BusinessPartner::TABLE)->delete();
        $this->db->table(BusinessPartner::TABLE)->insert([
            [
                'id'   => 1,
                'name' => 'bp1',
            ],
            [
                'id'   => 2,
                'name' => 'bp2',
            ],
        ]);

        $this->db->table(WarehouseOrder::TABLE)->delete();
        $this->db->table(WarehouseOrder::TABLE)->insert([
            [
                'id'                            => 1,
                'order_origin'                  => WarehouseOrder::ORDER_ORIGIN_SALES,
                'transaction_type'              => WarehouseOrder::TRANSACTION_TYPE_ISSUE,
                'order'                         => 'SLS001',
                'ship_from_business_partner_id' => 0,
                'ship_from_warehouse_id'        => 1,
                'ship_from_work_center_id'      => 0,
                'ship_from_address_id'          => 1,
                'ship_to_business_partner_id'   => 1,
                'ship_to_warehouse_id'          => 0,
                'ship_to_work_center_id'        => 0,
                'ship_to_address_id'            => 2,
                'blocked'                       => 0,
            ],
            [
                'id'                            => 2,
                'order_origin'                  => WarehouseOrder::ORDER_ORIGIN_PURCHASE,
                'transaction_type'              => WarehouseOrder::TRANSACTION_TYPE_RECEIPT,
                'order'                         => 'PUR0012',
                'ship_from_business_partner_id' => 2,
                'ship_from_warehouse_id'        => 0,
                'ship_from_work_center_id'      => 0,
                'ship_from_address_id'          => 1,
                'ship_to_business_partner_id'   => 0,
                'ship_to_warehouse_id'          => 1,
                'ship_to_work_center_id'        => 0,
                'ship_to_address_id'            => 2,
                'blocked'                       => 0,
            ],
            [
                'id'                            => 3,
                'order_origin'                  => WarehouseOrder::ORDER_ORIGIN_PRODUCTION,
                'transaction_type'              => WarehouseOrder::TRANSACTION_TYPE_RECEIPT,
                'order'                         => 'PRD001',
                'ship_from_business_partner_id' => 0,
                'ship_from_warehouse_id'        => 0,
                'ship_from_work_center_id'      => 2,
                'ship_from_address_id'          => 1,
                'ship_to_business_partner_id'   => 0,
                'ship_to_warehouse_id'          => 1,
                'ship_to_work_center_id'        => 0,
                'ship_to_address_id'            => 2,
                'blocked'                       => 0,
            ],
            [
                'id'                            => 4,
                'order_origin'                  => WarehouseOrder::ORDER_ORIGIN_PRODUCTION,
                'transaction_type'              => WarehouseOrder::TRANSACTION_TYPE_ISSUE,
                'order'                         => 'PRD002',
                'ship_from_business_partner_id' => 0,
                'ship_from_warehouse_id'        => 2,
                'ship_from_work_center_id'      => 0,
                'ship_from_address_id'          => 1,
                'ship_to_business_partner_id'   => 0,
                'ship_to_warehouse_id'          => 0,
                'ship_to_work_center_id'        => 1,
                'ship_to_address_id'            => 2,
                'blocked'                       => 0,
            ],
        ]);
    }
}
