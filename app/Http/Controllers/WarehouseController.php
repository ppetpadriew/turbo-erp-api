<?php

namespace App\Http\Controllers;


use App\Models\Warehouse;

class WarehouseController extends BaseController
{
    public function getModelClass(): string
    {
        return Warehouse::class;
    }
}
