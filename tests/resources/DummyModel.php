<?php

namespace App\Tests;


use App\Models\Model;

class DummyModel extends Model
{
    const TABLE = 'unit';
    const SCENARIO_X = 'x';
    const SCENARIO_Y = 'y';

    protected $fillable = [
        'code',
        'description',
    ];

    public function getRules(string $scenario): array
    {
        return [];
    }
}
