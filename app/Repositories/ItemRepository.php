<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository
{
    public function all()
    {
        return Item::all();
    }

    public function find($id)
    {
        return Item::find($id);
    }

    public function create($data)
    {
        return Item::create($data);
    }

    public function update($item, $data)
    {
        $item->update($data);
        return $item->refresh();
    }
}
