<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Models\Item;
use App\Models\ItemInventaris;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function show(ItemInventaris $item)
    {
        return view('livewire.items.show', compact('item'));
    }

    public function export()
    {
        return Excel::download(new ItemsExport,'items_data.xlsx');
    }

}
