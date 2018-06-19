<?php

namespace App\Tests;


use App\Models\Model;

class DummyModel extends Model
{
    const TABLE = 'unit';
    const SCENARIO_X = 'x';
    const SCENARIO_Y = 'y';

    public function getRules(): array
    {
        return [];
    }

    public function getFillable()
    {
        $fillable = [
            self::SCENARIO_CREATE => ['code', 'description'],
            self::SCENARIO_UPDATE => ['code', 'description'],
        ];

        return $fillable[$this->scenario];
    }

    public function getAttributeDefaultValues(): array
    {
        return [];
    }
}
