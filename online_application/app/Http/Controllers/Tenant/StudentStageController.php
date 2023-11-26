<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Student;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Response;

class StudentStageController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param Student $student
     * @return Factory|View
     */
    public function edit(Student $student)
    {
        $route = 'students.stage.update';

        return view(
            'back.students._partials.stage-edit',
            compact('student', 'route')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, Student $student)
    {
        $request->validate(
            [
                'stage' => 'required',
            ]
        );

        $student->stage = $request->stage;

        $student->save();

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['student_id' => $student->id],
            ]
        );
    }
}
