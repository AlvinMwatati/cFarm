<?php

namespace App\Http\Requests\Commodities;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\CommodityCategory;
use App\Enums\CommodityUnit;

class StoreCommodityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow users with the 'admin' role to create commodities
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255', 'unique:commodities,name'],
            'category'    => ['required', new Enum(CommodityCategory::class)],
            'unit'        => ['required', new Enum(CommodityUnit::class)],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
