<?php

namespace App\Models;


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

    public function getRules(): array
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
