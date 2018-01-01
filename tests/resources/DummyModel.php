<?php

namespace App\Tests;


use App\Models\Model;

class DummyModel extends Model
{
    /** @var string */
    protected $table = 'unit';

    protected $fillable = [
        'code',
        'description'
    ];
}
