<?php

namespace App\Models;


class Unit extends Model
{
    const TABLE = 'unit';

    /** @var string */
    protected $table = self::TABLE;

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'code'        => 'required|unique:unit',
                'description' => 'required',
            ],
            self::SCENARIO_UPDATE => [],
        ];

        return $scenario
            ? $rules[$scenario]
            : $rules;
    }

    public function getFillable(): array
    {
        $fillable = [
            self::SCENARIO_CREATE => ['code', 'description'],
            self::SCENARIO_UPDATE => ['description'],
        ];

        return $fillable[$this->scenario];
    }

    // Relationships
}
