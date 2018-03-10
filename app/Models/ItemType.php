<?php

namespace App\Models;


use Illuminate\Validation\Rule;

class ItemType extends Model
{
    const TABLE = 'item_type';

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'description' => ['required', "unique:{$this->table}"],
            ],
            self::SCENARIO_UPDATE => [
                'description' => Rule::unique($this->table)->ignore($this->id),
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
}