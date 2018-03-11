<?php

namespace App\Tests;

use App\Http\Controllers\BaseController;

class DummyController extends BaseController
{
    public function getModelClass(): string
    {
        return '';
    }
}
