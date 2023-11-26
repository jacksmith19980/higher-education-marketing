<?php

namespace App\Tenant\Traits\School;

use Illuminate\Http\Request;
use Response;

trait Toggleable
{
    public function toggle($request)
    {
        $model = 'App\\Tenant\\Models\\'.ucwords($request['model']);
        $model = $model::find($request['id']);
        $prop = $model->{$request['prop']};

        $model->update([
            $request['prop'] => ! $prop,
        ]);
        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                'id'        => $request['id'],
                'status'    => !$prop,
            ],
        ]);
    }
}
