<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

/**
 * Class Item
 * @package App\Models
 *
 * @property int $id
 * @property string $code
 * @property string $ean
 * @property string $item_type
 * @property int $inventory_unit_id
 * @property double $weight
 * @property int $weight_unit_id
 * @property boolean $lot_controlled
 */
class Item extends Model
{
    const TABLE = 'item';
    const ITEM_TYPE_PURCHASED = 'Purchased';
    const ITEM_TYPE_MANUFACTURED = 'Manufactured';
    const ITEM_TYPES = [
        self::ITEM_TYPE_PURCHASED,
        self::ITEM_TYPE_MANUFACTURED,
    ];

    public function getAttributeDefaultValues(): array
    {
        return [
            'lot_controlled' => false,
        ];
    }

    public function getRules(): array
    {
        return [
            ['required', ['code'], [self::SCENARIO_CREATE]],
            ['required', ['item_type', 'inventory_unit_id', 'weight', 'weight_unit_id', 'lot_controlled']],
            ['max:20', ['code'], [self::SCENARIO_CREATE]],
            ['max:13', ['ean']],
            ['max:100', ['description']],
            ["unique:{$this->table}", ['code'], [self::SCENARIO_CREATE]],
            [Rule::unique($this->table)->ignore($this->id), ['ean']],
            ['nullable', ['ean', 'description']],
            [Rule::in(self::ITEM_TYPES), ['item_type']],
            ['exists:' . Unit::TABLE . ',id', ['inventory_unit_id', 'weight_unit_id']],
            ['numeric', ['weight']],
            ['boolean', ['lot_controlled']],
        ];
    }

    // Relationships
}
