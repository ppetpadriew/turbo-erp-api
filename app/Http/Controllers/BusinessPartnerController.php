<?php

namespace App\Http\Controllers;


use App\Models\BusinessPartner;

class BusinessPartnerController extends BaseController
{

    public function getModelClass(): string
    {
        return BusinessPartner::class;
    }
}
