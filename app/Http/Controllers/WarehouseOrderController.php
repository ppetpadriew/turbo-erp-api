<?php

namespace App\Http\Controllers;


use App\Models\WarehouseOrder;

class WarehouseOrderController extends BaseController
{
    public function getModelClass(): string
    {
        return WarehouseOrder::class;
    }
}
