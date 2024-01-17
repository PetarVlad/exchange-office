<?php

namespace App\Domain\Currency\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $surcharge_percentage
 * @property mixed $discount_percentage
 * @property mixed $exchange_rate
 */
class Currency extends Model
{
    use HasFactory;
}
