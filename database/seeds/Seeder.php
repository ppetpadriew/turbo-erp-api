<?php

namespace App\Database\Seeds;

use App\Constants\Service;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Facade;

class Seeder extends \Illuminate\Database\Seeder
{
    /** @var DatabaseManager */
    protected $db;
    
    public function __construct()
    {
        $this->db = Facade::getFacadeApplication()->make(Service::DB);
    }
}
