<?php

namespace App\Actions\Listings;

use App\DTOs\Listings\ListingData;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class CreateListingAction
{
    public function execute(ListingData $data, User $user, array $images = []): Listing
    {
        $listing = $user->listings()->create([
            'commodity_id'           => $data->commodity_id,
            'title'                  => $data->title,
            'description'            => $data->description,
            'price_per_unit'         => $data->price_per_unit,
            'quantity_available'     => $data->quantity_available,
            'minimum_order_quantity' => $data->minimum_order_quantity,
            'county'                 => $data->county,
            'town'                   => $data->town,
        ]);

        // Attach uploaded images
        foreach ($images as $image) {
            $listing->addMedia($image)
                    ->toMediaCollection('images');
        }

        return $listing;
    }
}
