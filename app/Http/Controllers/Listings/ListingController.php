<?php

namespace App\Http\Controllers\Listings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listings\StoreListingRequest;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Commodity;
use App\Enums\KenyaCounty;
use App\Actions\Listings\CreateListingAction;
use App\DTOs\Listings\ListingData;
use App\Enums\CommodityCategory;
use App\Actions\Listings\FilterListingsAction;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FilterListingsAction $filter): \Illuminate\View\View
{
    $listings    = $filter->execute($request);
    $commodities = Commodity::active()->orderBy('name')->get();
    $categories  = CommodityCategory::cases();
    $counties    = KenyaCounty::cases();

    return view('listings.index', compact(
        'listings',
        'commodities',
        'categories',
        'counties',
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $commodities = Commodity::active()->orderBy('name')->get();
        $counties    = KenyaCounty::cases();

        return view('listings.create', compact('commodities', 'counties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListingRequest $request, CreateListingAction $action)
    {
        $data = ListingData::from($request->safe()->except('images'));

        $action->execute(
            data:   $data,
            user:   $request->user(),
            images: $request->file('images', [])
        );

        return redirect()->route('listings.index')
            ->with('success', 'Your listing has been posted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {

        $listing->load(['user', 'commodity', 'media']);

        return view('listings.show', compact('listing'));
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
    public function update(StoreListingRequest $request, string $id)
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

    // My listings
    public function myListings(Request $request)
    {
        $listings = $request->user()
            ->listings()
            ->with(['commodity', 'media'])
            ->latest()
            ->paginate(10);

        return view('listings.my-listings', compact('listings'));
    }

    // Toggle active/inactive
    public function toggleStatus(Listing $listing)
    {
        $this->authorize('update', $listing);

        $listing->update([
            'status' => $listing->status === \App\Enums\ListingStatus::ACTIVE
                ? \App\Enums\ListingStatus::INACTIVE
                : \App\Enums\ListingStatus::ACTIVE,
        ]);

        return back()->with('success', 'Listing status updated.');
    }
}
