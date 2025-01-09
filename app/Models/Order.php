<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
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
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'purchased_amount',
        'exchange_rate',
        'paid_amount',
        'surcharge_percentage',
        'surcharge_amount',
        'discount_percentage',
        'discount_amount',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
