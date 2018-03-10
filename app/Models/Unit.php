<?php

namespace App\Models;


class Unit extends Model
{
    const TABLE = 'unit';

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'code'        => ['required', "unique:{$this->table}", 'max:3'],
                'description' => ['required', 'max:40'],
            ],
            self::SCENARIO_UPDATE => [
                'description' => ['max:40'],
            ],
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
