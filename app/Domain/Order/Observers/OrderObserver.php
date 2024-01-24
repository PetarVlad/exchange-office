<?php

namespace App\Domain\Order\Observers;

use App\Domain\Order\Mail\OrderCreated;
use App\Domain\Order\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function created(Order $order): void
    {
        if(in_array($order->currency->iso, config('notifications.order.currency_iso'))){
            Mail::to(config('notifications.recipient'))->queue(new OrderCreated($order));
        }
    }
}
