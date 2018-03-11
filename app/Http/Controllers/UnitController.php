<?php

namespace App\Http\Controllers;


use App\Models\Unit;

class UnitController extends BaseController
{
    public function getModelClass(): string
    {
        return Unit::class;
    }
}
