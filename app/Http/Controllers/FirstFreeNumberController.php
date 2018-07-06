<?php

namespace App\Http\Controllers;


use App\Models\FirstFreeNumber;

class FirstFreeNumberController extends BaseController
{
    public function getModelClass(): string
    {
        return FirstFreeNumber::class;
    }
}
