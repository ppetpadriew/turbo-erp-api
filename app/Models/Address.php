<?php

namespace App\Models;


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
    public function businessPartners()
    {
        return $this->hasMany(BusinessPartner::class);
    }
}
