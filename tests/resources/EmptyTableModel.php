<?php

namespace App\Tests;


use App\Models\Model;

class EmptyTableModel extends Model
{
    public function getRules(): array
    {
        return [];
    }

    public function getAttributeDefaultValues(): array
    {
        return [];
    }
}
