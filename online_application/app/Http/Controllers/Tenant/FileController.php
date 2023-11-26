<?php

namespace App\Http\Controllers\Tenant;

use Auth;
use App\Tenant\Models\File;
use Illuminate\Http\Request;
use App\Tenant\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store($fileName, $originalFileName, $size, $type = null, $student_id = null)
    {
        if ($student_id == null) {
            $student = auth()->guard('student')->user();
        } else {
            $student = Student::findOrFail($student_id);
        }

        $file = new File();
        $file->name = $fileName;
        $file->original_name = $originalFileName;
        $file->size = $size;
        $file->type = $type;
        $file->student()->associate($student);
        if ($file->save()) {
            return $file;
        }
    }


    public function showFile(Request $request)
    {
        if(!$request->filled('fileName')) {
            return abort(404);
        }
        $path = Storage::disk('s3')->temporaryUrl($request->fileName, now()->addSeconds(30));
        return redirect($path);
    }

    public function destroy(Request $request)
    {
        // Delete frile from DB

        if ($fileName = $request->file) {
            $file = File::byName($fileName)->firstOrFail();

            // not Authorized

            if ($file->student_id != Auth::guard('student')->user()->id) {
                return Response::json([
                    'status' => 403,
                    'response' => 'Unauthorized',
                    'extra' => ['message' => 'You are not aut horized to delete this file'],
                ]);
            }

            if (Storage::delete($request->file) && $file->delete()) {
                return Response::json([
                    'status' => 200,
                    'response' => 'success',
                    'extra' => ['file' => $request->file],
                ]);
            }
        }
    }
}
