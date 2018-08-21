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

    public function getRules(): array
    {
        return [
            ['required', ['title', 'name', 'language', 'address_id']],
            ['max:3', ['title', 'language']],
            ['max:35', ['name']],
            ["unique:{$this->table}", ['name'], [self::SCENARIO_CREATE]],
            [Rule::unique($this->table)->ignore($this->id), ['name'], [self::SCENARIO_UPDATE]],
            // @todo: add exists validation to address_id field
            // ['exists:'.Address::TABLE.',id', ['address_id']],
        ];
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
