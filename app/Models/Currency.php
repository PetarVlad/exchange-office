<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $iso
 * @property mixed $exchange_rate
 * @property mixed $surcharge_percentage
 * @property mixed $discount_percentage
 */
class Currency extends Model
{
    use HasFactory;

    protected $casts = [
        'exchange_rate' => 'float',
        'surcharge_percentage' => 'float',
        'discount_percentage' => 'float',
    ];
}
