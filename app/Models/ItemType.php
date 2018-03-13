<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

/**
 * Class ItemType
 * @package App\Models
 *
 * @property int $id
 * @property string $description
 */
class ItemType extends Model
{
    const TABLE = 'item_type';

    public function getAttributeDefaultValues(): array
    {
        return [];
    }

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'description' => ['required', 'max:30', "unique:{$this->table}"],
            ],
            self::SCENARIO_UPDATE => [
                'description' => ['required', 'max:30', Rule::unique($this->table)->ignore($this->id)],
            ],
        ];

        return $scenario
            ? $rules[$scenario]
            : $rules;
    }

    public function getFillable()
    {
        $fillable = [
            self::SCENARIO_CREATE => ['description'],
            self::SCENARIO_UPDATE => ['description'],
        ];

        return $fillable[$this->scenario];
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
