<?php

namespace App\Tests;


use App\Models\Model;

class NotImplementGetFillableModel extends Model
{
    const TABLE = 'something';

    public function getRules(string $scenario): array
    {
        return [];
    }

    public function getAttributeDefaultValues(): array
    {
        return [];
    }
}
