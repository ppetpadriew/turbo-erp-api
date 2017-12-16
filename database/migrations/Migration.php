<?php

namespace App\Database\Migrations;

use App\Constants\Service;
use Illuminate\Support\Facades\Facade;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = Facade::getFacadeApplication()->make(Service::DB);
    }
}
