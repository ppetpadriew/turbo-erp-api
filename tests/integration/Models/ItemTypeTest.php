<?php

namespace App\Tests\Integration\Models;

use App\Database\Seeds\ItemTypeSeeder;
use App\Models\Item;
use App\Models\ItemType;
use App\Tests\Integration\Integration;

class ItemTypeTest extends Integration
{
    public function testItems()
    {
        $this->specify('It should return array of Item instances.', function () {
            (new ItemTypeSeeder)->testItems();

            $items = ItemType::find(1)->items;

            verify($items)->notEmpty();
            foreach ($items as $item) {
                verify($item)->isInstanceOf(Item::class);
            }
        });
    }
}
