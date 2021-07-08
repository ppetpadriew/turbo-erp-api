<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkCenterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_center', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', '6');
            $table->string('description', '30');
            $table->enum('type', ['Work Center', 'Sub Work Center', 'SubContracting Work Center', 'Costing Work Center']);
            $table->boolean('blocked');
            $table->double('wait_time', 6, 2);
            $table->double('move_time', 6, 2);
            $table->double('queue_time', 6, 2);
            $table->integer('time_unit_id');
            $table->integer('shop_floor_warehouse_id');
            $table->integer('backflush_employee_id');
            $table->integer('parent_work_center_id');
            $table->integer('costing_work_center_id');
            $table->integer('operation_rate_id');
            $table->integer('production_department_id');
            $table->timestamps();

            $table->index(['code'], 'code');
            $table->index(['type', 'code'], 'type_code');
            $table->index(['parent_work_center_id'], 'parent_work_center_id');
            $table->index(['parent_work_center_id', 'code'], 'parent_work_center_id_code');
            $table->index(['time_unit_id'], 'time_unit_id');
            $table->index(['shop_floor_warehouse_id'], 'shop_floor_warehouse_id');
            $table->index(['backflush_employee_id'], 'backflush_employee_id');
            $table->index(['costing_work_center_id'], 'costing_work_center_id');
            $table->index(['operation_rate_id'], 'operation_rate_id');
            $table->index(['production_department_id'], 'production_department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_center');
    }
}
