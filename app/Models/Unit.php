<?php

namespace App\Models;


class Unit extends Model {

    const TABLE = 'unit';
    /** @var string */
    protected $table = self::TABLE;

    protected $fillable = [
        'code',
        'description'
    ];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
