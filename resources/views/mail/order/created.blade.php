Order #{{$order->id}} created.
Details
Currency purchased: {{$order->purchased_amount}} {{$order->currency->iso}}
Exchange rate: {{$order->exchange_rate}} for 1 {{$defaultCurrency}}
Converted amount: {{round($order->purchased_amount / $order->exchange_rate, 2)}} {{$defaultCurrency}}
Surcharge: +{{$order->surcharge_percentage}}% (+{{$order->surcharge_amount}} {{$defaultCurrency}})
Discount: -{{$order->discount_percentage}}% (-{{$order->discount_amount}} {{$defaultCurrency}})
Total: {{$order->paid_amount}} {{$defaultCurrency}}

Best regards
