<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

/**
 * Class BusinessPartner
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $language
 * @property int $address_id
 */
class BusinessPartner extends Model
{
    const TABLE = 'business_partner';

    public function getRules(string $scenario): array
    {
        $rules = [
            self::SCENARIO_CREATE => [
                'title'      => ['required', 'max:3'],
                'name'       => ['required', 'max:35', "unique:{$this->table}"],
                'language'   => ['required', 'max:3'],
                'address_id' => ['required', 'exists:' . Address::TABLE . ',id'],
            ],
            self::SCENARIO_UPDATE => [
                'title'      => ['required', 'max:3'],
                'name'       => ['required', 'max:35', Rule::unique($this->table)->ignore($this->id)],
                'language'   => ['required', 'max:3'],
                'address_id' => ['required', 'exists:' . Address::TABLE . ',id'],
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
                'title',
                'name',
                'language',
                'address_id',
            ],
            self::SCENARIO_UPDATE => [
                'title',
                'name',
                'language',
                'address_id',
            ],
        ];

        return $fillable[$this->scenario];
    }

    public function getAttributeDefaultValues(): array
    {
        return [];
    }

    // Relations

    /**
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
