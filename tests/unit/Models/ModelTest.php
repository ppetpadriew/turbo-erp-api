<?php

namespace App\Tests\Unit\Models;

use App\Tests\DummyModel;
use App\Tests\Unit\Unit;

class ModelTest extends Unit
{
    public function testSetScenario()
    {
        $this->specify('It should not throw an exception when scenario is valid.', function () {
            (new DummyModel)->setScenario(DummyModel::SCENARIO_X);
        });

        $this->specify('It should throw an exception when scenario is invalid.', function () {
            (new DummyModel)->setScenario('invalid');
        }, [
            'throws' => \InvalidArgumentException::class,
        ]);
    }

    public function testGetScenarios()
    {
        $this->specify('It should get all constants that begin with "SCENARIO_"', function () {
            $scenarios = (new DummyModel)->getScenarios();

            verify($scenarios)->equals([
                'SCENARIO_CREATE' => 'create',
                'SCENARIO_UPDATE' => 'update',
                'SCENARIO_X'      => 'x',
                'SCENARIO_Y'      => 'y',
            ]);
        });
    }

    public function testFillable()
    {
        $this->specify('It should throw an exception when calling.', function () {
            (new DummyModel)->fillable([]);
        }, [
            'throws' => \Exception::class,
        ]);
    }
}
