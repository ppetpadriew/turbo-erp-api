<?php

namespace App\Models;

/**
 * Class Warehouse
 * @package App\Models
 *
 * @property int $id
 * @property string $code
 * @property string $description
 * @property boolean $negative_inventory_allowed
 * @property boolean $manual_adjustment_allowed
 */
class Warehouse extends Model
{
    const TABLE = 'warehouse';

    public function getAttributeDefaultValues(): array
    {
        return [
            'negative_inventory_allowed' => 0,
            'manual_adjustment_allowed'  => 1,
        ];
    }

    protected function getRules(): array
    {
        return [
            ['required', ['code'], [self::SCENARIO_CREATE]],
            ['required', ['negative_inventory_allowed', 'manual_adjustment_allowed']],
            ["unique:{$this->table}", ['code'], [self::SCENARIO_CREATE]],
            ['max:6', ['code'], [self::SCENARIO_CREATE]],
            ['max:30', ['description']],
            ['nullable', ['description']],
            ['bool', ['negative_inventory_allowed', 'manual_adjustment_allowed']],
        ];
    }

}
