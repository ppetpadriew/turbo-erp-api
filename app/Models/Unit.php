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

    public function getRules(): array
    {
        return [
            ['required', ['code'], [self::SCENARIO_CREATE]],
            ["unique:{$this->table}", ['code'], [self::SCENARIO_CREATE]],
            ['max:3', ['code'], [self::SCENARIO_CREATE]],
            ['nullable', ['description']],
            ['max:40', ['description']],
        ];
    }

    // Relationships
}
