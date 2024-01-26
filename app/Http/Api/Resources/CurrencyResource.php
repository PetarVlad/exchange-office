<?php

namespace App\Http\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $iso
 * @property float $exchange_rate
 * @property float $surcharge_percentage
 * @property float $discount_percentage
 */
class CurrencyResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'iso' => $this->iso,
            'exchange_rate' => $this->exchange_rate,
            'surcharge_percentage' => $this->surcharge_percentage,
            'discount_percentage' => $this->discount_percentage
        ];
    }
}
