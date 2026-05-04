<?php

namespace App\Http\Requests\Listings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\KenyaCounty;
use Illuminate\Validation\Rules\Enum;

class StoreListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null; // Only allow authenticated users to create listings
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'commodity_id'           => ['required', 'exists:commodities,id'],
            'title'                  => ['required', 'string', 'max:255'],
            'description'            => ['nullable', 'string', 'max:2000'],
            'price_per_unit'         => ['required', 'numeric', 'min:1'],
            'quantity_available'     => ['required', 'integer', 'min:1'],
            'minimum_order_quantity' => ['required', 'integer', 'min:1', 'lte:quantity_available'],
            'county'                 => ['required', 'string', new Enum(KenyaCounty::class)],
            'town'                   => ['nullable', 'string', 'max:255'],
            'images'                 => ['nullable', 'array', 'max:5'],
            'images.*'               => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }

     public function messages(): array
    {
        return [
            'minimum_order_quantity.lte' => 'Minimum order cannot exceed total quantity available.',
            'images.max'                 => 'You can upload a maximum of 5 images.',
            'images.*.image'             => 'Each file must be a valid image.',
        ];
    }


}
