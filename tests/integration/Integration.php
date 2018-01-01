<?php

namespace App\Tests\Integration;

use App\Constants\Service;
use App\Tests\TestCase;
use Illuminate\Database\Connection;

class Integration extends TestCase
{
    /** @var Connection */
    protected $db;

    public function createApplication()
    {
        $app =  parent::createApplication();
        $this->db = $this->app[Service::DB];

        return $app;
    }
}
