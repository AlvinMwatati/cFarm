<?php

namespace App\Actions\Listings;

use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class FilterListingsAction
{
    public function execute(Request $request): LengthAwarePaginator
    {
        $query = Listing::active()
            ->with(['user', 'commodity', 'media']);

        // Keyword search across title and description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by commodity
        if ($request->filled('commodity_id')) {
            $query->byCommodity((int) $request->commodity_id);
        }

        // Filter by category (goes through commodity relationship)
        if ($request->filled('category')) {
            $query->whereHas('commodity', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Filter by county
        if ($request->filled('county')) {
            $query->byCounty($request->county);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_per_unit', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_unit', '<=', (float) $request->max_price);
        }

        // Sorting
        $query = match ($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price_per_unit', 'asc'),
            'price_desc' => $query->orderBy('price_per_unit', 'desc'),
            default      => $query->latest(),
        };

        return $query->paginate(12)->withQueryString();
    }
}
