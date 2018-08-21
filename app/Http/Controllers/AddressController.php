<?php

namespace App\Http\Controllers;


use App\Models\Address;

class AddressController extends BaseController
{

    public function getModelClass(): string
    {
        return Address::class;
    }
}
