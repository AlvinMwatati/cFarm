<?php

namespace App\Actions\Commodities;

use App\DTOs\Commodities\CommodityData;
use App\Models\Commodity;

class CreateCommodityAction
{
    public function execute(CommodityData $data): Commodity
    {
        return Commodity::create([
            'name'        => $data->name,
            'category'    => $data->category,
            'unit'        => $data->unit,
            'description' => $data->description,
        ]);
    }
}
