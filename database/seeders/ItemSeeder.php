<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::factory(10)->create()->each(function ($item){
            ItemStatus::factory()->create([
                'item_id' => $item->id
            ]);
        });
    }
}
