<?php

namespace App\Http\Api\Controllers;

use App\Http\Api\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class CurrencyResourceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CurrencyResource::collection(Currency::all());
    }
}
