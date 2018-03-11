<?php

namespace App\Tests;


use App\Models\Model;

class EmptyTableModel extends Model
{
    public function getRules(string $scenario): array
    {
        return [];
    }

    public function getAttributeDefaultValues(): array
    {
        return [];
    }
}
