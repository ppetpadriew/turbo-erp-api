<?php

namespace App\Models;


class Unit extends Model
{
    const TABLE = 'unit';

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'code'        => "required|unique:{$this->table}",
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
