<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;
use App\Http\Requests\Commodities\StoreCommodityRequest;
use App\Actions\Commodities\CreateCommodityAction;
use App\DTOs\Commodities\CommodityData;

class CommodityController extends Controller
{
    public function index()
    {
        $commodities = Commodity::withCount('listings')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.commodities.index', compact('commodities'));
    }

    public function create()
    {
        $categories = CommodityCategory::cases();
        $units      = CommodityUnit::cases();
        return view('admin.commodities.create', compact('categories', 'units'));
    }

    public function store(StoreCommodityRequest $request, CreateCommodityAction $action)
    {
        $data = CommodityData::from([
            ...$request->validated(),
            'category' => CommodityCategory::from($request->category),
            'unit'     => CommodityUnit::from($request->unit),
        ]);

        $action->execute($data);

        return redirect()->route('admin.commodities')
            ->with('success', 'Commodity created.');
    }

    public function toggleActive(Commodity $commodity)
    {
        $commodity->update(['is_active' => !$commodity->is_active]);

        $status = $commodity->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Commodity {$status}.");
    }
}
