<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class TaxController extends Controller
{
    public function __construct()
    {
        $this->middleware(['tenant']);
    }

    public function getTaxTemplate($payload)
    {
        $html = view('back.settings.tax.tax-rules')->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html'  => $html],
        ]);
    }

    public function getTaxRuleTemplate($payload)
    {
        $index = (isset($payload['index'])) ? $payload['index'] : 0;
        $rule = false;

        $html = view('back.settings.tax._partials.tax-rule', compact('index', 'rule'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html'  => $html],
        ]);
    }
}
