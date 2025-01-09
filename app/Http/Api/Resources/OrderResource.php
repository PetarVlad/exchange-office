<?php

namespace App\Http\Api\Resources;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $iso
 * @property int $currency_id
 * @property float $purchased_amount
 * @property float $exchange_rate
 * @property float $paid_amount
 * @property float $surcharge_percentage
 * @property float $surcharge_amount
 * @property float $discount_percentage
 * @property float $discount_amount
 * @property Currency $currency
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'currency_iso' => $this->currency->iso,
            'purchased_amount' => $this->purchased_amount,
            'exchange_rate' => $this->exchange_rate,
            'paid_amount' => $this->paid_amount,
            'surcharge_percentage' => $this->surcharge_percentage,
            'surcharge_amount' => $this->surcharge_amount,
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $this->discount_amount,
        ];
    }
}
