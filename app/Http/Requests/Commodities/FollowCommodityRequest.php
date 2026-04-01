<?php

namespace App\Http\Requests\Commodities;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FollowCommodityRequest extends FormRequest
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
            'notify_price_drop'     => ['boolean'],
            'notify_price_spike'    => ['boolean'],
            'notify_new_listing'    => ['boolean'],
            'notify_weekly_summary' => ['boolean'],
            'via_email'             => ['boolean'],
            'via_sms'               => ['boolean'],
            'price_change_threshold' => ['numeric', 'min:1', 'max:100'],
        ];
    }

}
