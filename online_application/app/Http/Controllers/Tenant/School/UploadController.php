<?php

namespace App\Http\Controllers\Tenant\School;

use App\Helpers\Permission\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenant\FileController as FileController;
use App\Tenant\Models\File;
use Auth;
use Illuminate\Http\Request;
use Response;
use Storage;

class UploadController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['tenant', 'auth:student']);
    }

    public function upload(Request $request)
    {
        if (! Auth::guard('student')->user()) {
            return Response::json([
                'status'    => 403,
                'response'  => 'Unauthorized',
                'extra'     => ['message' => __('You are not authorized')],
            ]);
        }

        $originalFileName = $request->documents->getClientOriginalName();
        $size = ($request->documents->getSize()) / 1000 .'KB';
        $type = ($request->has('type')) ? $request->type : null;
        $student_id = ($request->has('student_id')) ? $request->student_id : null;
        //if($fileName =  $request->documents->store('documents')){
        if ($fileName = Storage::putFile('/'.session('tenant'), $request->documents)) {
            // Store File In Files Table
            $storedFile = app(FileController::class)->store($fileName, $originalFileName, $size, $type, $student_id);

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => [
                    'file'                  => $storedFile->name,
                    'originalFileName'      => $storedFile->original_name,
                    'type'                  => $type,
                ],
            ]);
        }
    }

    public function destroy(Request $request)
    {
        if (! Auth::guard('student')->user()) {
            return Response::json([
                'status'    => 403,
                'response'  => 'Unauthorized',
                'extra'     => ['message' => __('You are not authorized')],
            ]);
        }

        // Delete frile from DB
        if ($fileName = $request->file) {
            $file = File::byName($fileName)->firstOrFail();

            // not Authorized
            if (Auth::guard('student')->user() && $file->student_id != Auth::guard('student')->user()->id) {
                return Response::json([
                    'status'    => 403,
                    'response'  => 'Unauthorized',
                    'extra'     => ['message' => __('You are not authorized to delete this file')],
                ]);
            }

            if (Storage::delete($request->file) && $file->delete()) {
                return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => [
                        'file' => $request->file,
                        'type' => $file->type,
                    ],
                ]);
            }
        }
    }
}
