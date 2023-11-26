<?php

namespace App\Widgets;

use App\Http\Resources\School\FollowupResource;
use App\Tenant\Models\Followup;
use App\Widgets\Widgets;
use Response;

class CallbackRequests extends Widgets
{
    public function build()
    {
        $followups = Followup::orderBy('id', 'DESC')->take(5)->get();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'data'      => [
                'followups' => FollowupResource::collection($followups),
            ],
        ]);
    }
}
