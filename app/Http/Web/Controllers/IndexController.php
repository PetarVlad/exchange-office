<?php

namespace App\Http\Web\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class IndexController extends Controller
{
    public function index(): View
    {
        return view('index', [
            'defaultCurrency' => config('currencies.default'),
        ]);
    }
}
