<?php

namespace App\Models;

/**
 * Class Unit
 * @package App\Models
 *
 * @property int $id
 * @property string $code
 * @property string $description
 */
class Unit extends Model
{
    const TABLE = 'unit';

    public function getAttributeDefaultValues(): array
    {
        return [];
    }

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'code'        => ['required', "unique:{$this->table}", 'max:3'],
                'description' => ['required', 'max:40'],
            ],
            self::SCENARIO_UPDATE => [
                'description' => ['required', 'max:40'],
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
