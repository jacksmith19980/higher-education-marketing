<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Response;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['tenant']);
    }

    public function upload(Request $request)
    {
        $originalFileName = $request->documents->getClientOriginalName();

        if ($file = $request->documents->store('documents')) {
            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['file' => $file, 'originalFileName' => $originalFileName],
            ]);
        }
    }
}
