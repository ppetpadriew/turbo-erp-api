<?php

namespace App\Tests;


use App\Models\Model;

class EmptyTableModel extends Model
{
    protected function getRules(): array
    {
        return [];
    }

    public function getAttributeDefaultValues(): array
    {
        return [];
    }
}
