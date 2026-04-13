<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Enums\ListingStatus;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $listings = Listing::with(['user', 'commodity'])
            ->when($request->search, fn ($q) =>
                $q->where('title', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->county, fn ($q) =>
                $q->where('county', $request->county)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.listings.index', compact('listings'));
    }

    public function toggleStatus(Listing $listing)
    {
        $listing->update([
            'status' => $listing->status === ListingStatus::ACTIVE
                ? ListingStatus::INACTIVE
                : ListingStatus::ACTIVE,
        ]);

        return back()->with('success', 'Listing status updated.');
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();
        return back()->with('success', 'Listing deleted.');
    }
}
