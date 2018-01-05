<?php

namespace App\Tests\Integration;

use App\Constants\Service;
use App\Tests\Unit\Unit;
use Illuminate\Support\Facades\Facade;

/**
 * It is kinda like Unit but features that use multiple classes/methods are tested.
 * @property-read \IntegrationTester $tester
 */
class Integration extends Unit
{
    protected $db;

    public function _before()
    {
        parent::_before();

        $this->db = Facade::getFacadeApplication()->make(Service::DB);
    }
}
