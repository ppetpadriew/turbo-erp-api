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

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'code'                       => ['required', 'max:6', "unique:{$this->table}"],
                'description'                => ['nullable', 'max:30'],
                'negative_inventory_allowed' => ['required', 'boolean'],
                'manual_adjustment_allowed'  => ['required', 'boolean'],
            ],
            self::SCENARIO_UPDATE => [
                'description'                => ['nullable', 'max:30'],
                'negative_inventory_allowed' => ['required', 'boolean'],
                'manual_adjustment_allowed'  => ['required', 'boolean'],
            ],
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
                'description',
                'negative_inventory_allowed',
                'manual_adjustment_allowed',
            ],
            self::SCENARIO_UPDATE => [
                'description',
                'negative_inventory_allowed',
                'manual_adjustment_allowed',
            ],
        ];

        return $fillable[$this->scenario];
    }
}
