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
 * @property int $item_type_id
 * @property int $inventory_unit_id
 * @property double $weight
 * @property int $weight_unit_id
 * @property boolean $lot_controlled
 */
class Item extends Model
{
    const TABLE = 'item';

    /**
     * @return array
     */
    public function getAttributeDefaultValues(): array
    {
        return [
            'lot_controlled' => false,
        ];
    }

    /**
     * @param string $scenario
     * @return array
     */
    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'code'              => ['required', 'max:20', "unique:{$this->table}"],
                'ean'               => ['nullable', 'max:13', "unique:{$this->table}"],
                'description'       => ['nullable', 'max:100'],
                'item_type_id'      => ['required', 'exists:' . ItemType::TABLE . ',id'],
                'inventory_unit_id' => ['required', 'exists:' . Unit::TABLE . ',id'],
                'weight'            => ['required', 'numeric'],
                'weight_unit_id'    => ['required', 'exists:' . Unit::TABLE . ',id'],
                'lot_controlled'    => ['required', 'boolean'],
            ],
            self::SCENARIO_UPDATE => [
                'ean'               => ['nullable', 'max:13', Rule::unique($this->table)->ignore($this->id)],
                'description'       => ['nullable', 'max:100'],
                'item_type_id'      => ['required', 'exists:' . ItemType::TABLE . ',id'],
                'inventory_unit_id' => ['required', 'exists:' . Unit::TABLE . ',id'],
                'weight'            => ['required', 'numeric'],
                'weight_unit_id'    => ['required', 'exists:' . Unit::TABLE . ',id'],
                'lot_controlled'    => ['required', 'boolean'],
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
                'ean',
                'description',
                'item_type_id',
                'inventory_unit_id',
                'weight',
                'weight_unit_id',
                'lot_controlled',
            ],
            self::SCENARIO_UPDATE => [
                'ean',
                'description',
                'item_type_id',
                'inventory_unit_id',
                'weight',
                'weight_unit_id',
                'lot_controlled',
            ],
        ];

        return $fillable[$this->scenario];
    }

    // Relationships

    /**
     * @return BelongsTo
     */
    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }
}
