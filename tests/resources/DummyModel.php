<?php

namespace App\Tests;


use App\Models\Model;

class DummyModel extends Model
{
    const SCENARIO_X = 'x';
    const SCENARIO_Y = 'y';

    /** @var string */
    protected $table = 'unit';

    protected $fillable = [
        'code',
        'description',
    ];
}
