<?php

namespace App\Http\Controllers;


use App\Models\Item;

class ItemController extends BaseController
{
    public function getModelClass(): string
    {
        return Item::class;
    }
}
