<?php

namespace App\Http\Controllers;


use App\Models\ItemType;

class ItemTypeController extends BaseController
{
    public function getModelClass(): string
    {
        return ItemType::class;
    }
}
