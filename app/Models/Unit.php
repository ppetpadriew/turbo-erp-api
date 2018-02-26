<?php

namespace App\Models;


class Unit extends Model
{

    const TABLE = 'unit';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array */
    protected $fillable = [
        self::SCENARIO_CREATE => ['code', 'description'],
        self::SCENARIO_UPDATE => ['description'],
    ];

    /** @var array */
    public static $rules = [
        self::SCENARIO_CREATE => [
            'code'        => 'required',
            'description' => 'required',
        ],
        self::SCENARIO_UPDATE => [],
    ];

    public function getFillable(): array
    {
        return $this->fillable[$this->scenario];
    }

    // Relationships
}
