<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    const TABLE = 'item';

    public function getAttributeDefaultValues(): array
    {
        return [
            'lot_controlled' => false,
        ];
    }

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'code'              => ['required', 'max:20', "unique:{$this->table}"],
                'ean'               => ['nullable', 'max:13'],
                'description'       => ['nullable', 'max:100'],
                'item_type_id'      => ['required', 'exists:' . ItemType::TABLE . ',id'],
                'inventory_unit_id' => ['required', 'exists:' . Unit::TABLE . ',id'],
                'weight'            => ['required', 'numeric'],
                'weight_unit_id'    => ['required', 'exists:' . Unit::TABLE . ',id'],
                'lot_controlled'    => ['required', 'boolean'],
            ],
            self::SCENARIO_UPDATE => [],
        ];

        return $scenario
            ? $rules[$scenario]
            : $rules;
    }

    public function getFillable()
    {
        $fillable = [
            self::SCENARIO_CREATE => [
                'code',
                'ean',
                'description',
                'item_type_id',
                'inventory_unit_id',
                'weight',
                'weight_unit_id',
                'lot_controlled',
            ],
            self::SCENARIO_UPDATE => [],
        ];

        return $fillable[$this->scenario];
    }

    // Relationships

    public function itemType(): HasOne
    {
        return $this->hasOne(ItemType::class);
    }
}
