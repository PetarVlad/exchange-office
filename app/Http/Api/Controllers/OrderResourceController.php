<?php

namespace App\Http\Api\Controllers;

use App\Domain\Order\Actions\CreateOrderAction;
use App\Domain\Order\DataTransferObjects\OrderRequestDto;
use App\Http\Api\Requests\StoreOrderRequest;
use App\Http\Api\Resources\OrderResource;
use Illuminate\Routing\Controller;

class OrderResourceController extends Controller
{
    public function store(StoreOrderRequest $orderRequest, CreateOrderAction $createOrderAction): OrderResource
    {
        $validated = $orderRequest->safe()->all();

        $order = $createOrderAction(new OrderRequestDto(
            currency_iso: $validated['currency_iso'],
            purchased_amount: $validated['purchased_amount']
        ));

        return new OrderResource($order);
    }
}
