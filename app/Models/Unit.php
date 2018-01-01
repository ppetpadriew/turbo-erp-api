<?php

namespace App\Models;


class Unit extends Model {

    const TABLE = 'unit';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array */
    protected $fillable = [
        'code',
        'description'
    ];

    /** @var array */
    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
