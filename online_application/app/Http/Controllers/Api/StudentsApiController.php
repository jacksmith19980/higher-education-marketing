<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use App\Tenant\Models\Student;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Filters\HasApiFilters;


class StudentsApiController extends Controller
{
    use ApiResponseHelpers, HasApiFilters;

    public function index(Request $request)
    {
        $students = new Student;
        if($request->has('filters')){
            $students = $this->filter($request->filters , $students);
        }
        try{
            $students = $students->with([
            'submissions',
            ])->orderBy('id' , 'ASC')->paginate();

            return $this->respondWithSuccess([
                'data' => $students
            ]);

        } catch (\Exception $e) {
            return $this->respondError("Invalid Filters");
        }

    }

    public function show($school, $id = null, Request $request)
    {
        $student = Student::with('submissions')->find($id);
        return $this->respondWithSuccess([
                'data' => [
                    'student' => $student
                ]
            ]
        );
    }




}
?>
