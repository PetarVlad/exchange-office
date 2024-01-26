@extends('layouts.base')

@section('content')
    <div class="container">
        <h4 class="mt-4">Order #{{ $order->id }} created.</h4>
        <hr>

        <h5>Details</h5>

        <ul class="list-group">
            <li class="list-group-item">
                Currency purchased: {{ $order->purchased_amount }} {{ $order->currency->iso }}
            </li>
            <li class="list-group-item">
                Exchange rate: {{ $order->exchange_rate }} for 1 {{ $defaultCurrency }}
            </li>
            <li class="list-group-item">
                Converted amount: {{ round($order->purchased_amount / $order->exchange_rate, 2) }} {{ $defaultCurrency }}
            </li>
            <li class="list-group-item">
                Surcharge: +{{ $order->surcharge_percentage }}% (+{{ $order->surcharge_amount }} {{ $defaultCurrency }})
            </li>
            <li class="list-group-item">
                Discount: -{{ $order->discount_percentage }}% (-{{ $order->discount_amount }} {{ $defaultCurrency }})
            </li>
            <li class="list-group-item">
                Total: {{ $order->paid_amount }} {{ $defaultCurrency }}
            </li>
        </ul>

        <p class="mt-3">Best regards,<br>
        {{$appName}}</p>

    </div>
@endsection
