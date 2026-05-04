<?php

namespace App\Http\Controllers\Commodities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Http\Requests\Commodities\StoreCommodityRequest;
use App\DTOs\Commodities\CommodityData;
use App\Actions\Commodities\CreateCommodityAction;
use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commodities = Commodity::active()
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->groupBy(fn($c) => $c->category->label());

        return view('commodities.index', compact('commodities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // Only admins can manage commodities
    public function create()
    {
        $this->authorize('create', Commodity::class);

        $categories = CommodityCategory::cases();
        $units      = CommodityUnit::cases();

        return view('commodities.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreCommodityRequest $request,
        CreateCommodityAction $action
    ) {
        $data = CommodityData::from([
            ...$request->validated(),
            'category' => CommodityCategory::from($request->category),
            'unit'     => CommodityUnit::from($request->unit),
        ]);

        $action->execute($data);

        return redirect()->route('commodities.index')
            ->with('success', 'Commodity added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commodity $commodity)
    {
        return view('commodities.show', compact('commodity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
