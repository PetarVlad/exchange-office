<?php

namespace Tests\Unit\Domain\Currency\Order\Observers;

use App\Domain\Order\Mail\OrderCreated;
use App\Domain\Order\Observers\OrderObserver;
use App\Models\Currency;
use App\Models\Order;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderObserverTest extends TestCase
{
    public function test_after_created_mail_sent(): void
    {
        Mail::fake();

        $defaultCurrency = Config::get('notifications.order.currency_iso');
        Config::set('notifications.order.currency_iso', ['XYZ']);
        $order = Order::factory()->make([
            'currency_id' => null,
        ]);
        $order->currency = Currency::factory()->make([
            'iso' => 'XYZ',
        ]);
        $orderObserver = new OrderObserver;
        $orderObserver->created($order);
        Mail::assertQueued(OrderCreated::class, function (OrderCreated $mail) use ($order) {
            return $mail->order->currency->iso === $order->currency->iso;
        });
        Config::set('notifications.order.currency_iso', $defaultCurrency);
    }

    public function test_after_created_mail_not_sent(): void
    {
        Mail::fake();

        $defaultCurrency = Config::get('notifications.order.currency_iso');
        Config::set('notifications.order.currency_iso', ['XYZ']);
        $order = Order::factory()->make([
            'currency_id' => null,
        ]);
        $order->currency = Currency::factory()->make([
            'iso' => 'ABC',
        ]);
        $orderObserver = new OrderObserver;
        $orderObserver->created($order);
        Mail::assertNotQueued(OrderCreated::class);
        Config::set('notifications.order.currency_iso', $defaultCurrency);
    }
}
