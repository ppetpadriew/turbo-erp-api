<?php

namespace App\Models;

/**
 * Class Address
 * @package App\Models
 *
 * @property int $id
 * @property string $house_number
 * @property string $detail
 * @property string $street
 * @property string $district
 * @property string $sub_district
 * @property string $province
 * @property $zip_code
 * @property $country
 *
 */
class Address extends Model
{
    const TABLE = 'address';

    /**
     * @param string $scenario
     * @return array
     */
    public function getRules(string $scenario): array
    {
        // TODO: Implement getRules() method.
    }

    /**
     * @return array
     */
    public function getAttributeDefaultValues(): array
    {
        // TODO: Implement getAttributeDefaultValues() method.
    }
}
