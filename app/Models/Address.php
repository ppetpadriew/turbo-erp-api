<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    const TABLE = 'address';

    /**
     * @return array
     */
    protected function getRules(): array
    {
        return [
            ['required', ['house_number']],
            ['max:10', ['house_number', 'zip_code', 'country']],
            ['max:20', ['district', 'sub_district', 'province']],
            ['max:30', ['detail', 'street']],
        ];
    }

    /**
     * @return array
     */
    public function getAttributeDefaultValues(): array
    {
        return [];
    }

    // Relations

    /**
     * @return HasMany
     */
    public function businessPartners(): HasMany
    {
        return $this->hasMany(BusinessPartner::class);
    }
}
