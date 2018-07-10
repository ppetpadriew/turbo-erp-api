<?php

use App\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateWarehouseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_order', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('order_origin', [
                'Sales',
                'Sales (Manual)',
                'Purchase',
                'Purchase (Manual)',
                'Production',
                'Production (Manual)',
                'Transfer',
                'Transfer (Manual)',
            ]);
            $table->string('order', 9);
            $table->enum('status', ['Open', 'In Process', 'Receipt Open', 'Received', 'Shipment Open', 'Shipped', 'Transferred']);
            $table->integer('set');
            $table->enum('transaction_type', ['Issue', 'Receipt', 'Transfer']);
            $table->dateTime('order_datetime');
            $table->integer('ship_from_business_partner_id');
            $table->integer('ship_from_warehouse_id');
            $table->integer('ship_from_work_center_id');
            $table->integer('ship_from_address_id');
            $table->integer('ship_to_business_partner_id');
            $table->integer('ship_to_warehouse_id');
            $table->integer('ship_to_work_center_id');
            $table->integer('ship_to_address_id');
            $table->dateTime('delivery_datetime');
            $table->dateTime('receipt_datetime');
            $table->integer('warehouse_order_type_id');
            $table->boolean('blocked');
            $table->unique(['order'], 'order_unique');
            $table->index(['order_origin', 'status'], 'order_origin_order_status_index');
            $table->index(['ship_from_business_partner_id'], 'ship_from_business_partner_index');
            $table->index(['ship_from_warehouse_id'], 'ship_from_warehouse_index');
            $table->index(['ship_from_work_center_id'], 'ship_from_work_center_index');
            $table->index(['ship_from_address_id'], 'ship_from_address_index');
            $table->index(['ship_to_business_partner_id'], 'ship_to_business_partner_index');
            $table->index(['ship_to_warehouse_id'], 'ship_to_warehouse_index');
            $table->index(['ship_to_work_center_id'], 'ship_to_work_center_index');
            $table->index(['ship_to_address_id'], 'ship_to_address_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_order');
    }
}
