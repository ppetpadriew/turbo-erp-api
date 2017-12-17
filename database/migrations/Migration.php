<?php

namespace App\Database\Migrations;

use App\Constants\Service;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Facade;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
{
    /** @var ConnectionInterface */
    protected $db;

    public function __construct()
    {
        $this->db = Facade::getFacadeApplication()->make(Service::DB);
    }
}
