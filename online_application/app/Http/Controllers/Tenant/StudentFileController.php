<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\File;
use App\Tenant\Models\Student;
use Illuminate\Http\Request;
use Response;
use Storage;

class StudentFileController extends Controller
{
    public function destroy(School $school, Student $student, File $file, Request $request)
    {
        if ($student->id == $file->student_id) {
            if (Storage::delete($file->name) && $file->delete()) {
                return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['removedId' => $file->id],
                ]);
            }
        }
    }
}
