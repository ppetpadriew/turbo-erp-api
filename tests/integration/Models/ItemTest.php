<?php

namespace App\Tests\Integration\Models;


use App\Database\Seeds\ItemSeeder;
use App\Models\Item;
use App\Models\ItemType;
use App\Tests\Integration\Integration;

class ItemTest extends Integration
{
    public function testItemType()
    {
        $this->specify('It should return array of Item instances.', function () {
            (new ItemSeeder)->testItemType();

            $itemType = Item::find(1)->itemType;

            verify($itemType)->isInstanceOf(ItemType::class);
            verify($itemType->description)->equals('Purchased');
        });
    }
}
