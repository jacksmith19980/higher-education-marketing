<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class ValidationRulesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['tenant']);
    }

    public function create($payload)
    {
        $html = view('back.applications._partials.validation.'.$payload['type'], ['type' => $payload['type']])->render();

        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html' => $html, 'type' => $payload['type']],
        ]);
    }
}
