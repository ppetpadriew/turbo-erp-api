<?php

namespace App\Tests;

use App\Http\Controllers\BaseController;

class DummyController extends BaseController
{
    protected $modelClass = DummyModel::class;
}
