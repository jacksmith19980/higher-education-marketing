<?php

namespace App\Helpers\School;

use Illuminate\Support\Arr;
use App\Tenant\Models\Student;
use App\Tenant\Models\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\customfield\CustomFieldHelper;
use Schema;
use Str;

class StudentHelpers
{
    public static function getStudents()
    {
        return Student::stageStudents()->OrderLastName()->get();
    }

    public static function getStudentsInArrayOnlyNameId()
    {
        $students = Arr::pluck(Student::select("id" , DB::raw("CONCAT(students.first_name,' ',students.last_name) as full_name"))->get()->toArray(), 'full_name', 'id');

		return $students;
    }

    public static function getProfileFields()
    {
        $customFields = CustomFieldHelper::getContactsCustomFields('name', 'slug');
        $excludes = ["id", "agent_id", "parent_id", "password", "avatar", "remember_token", "created_at", "updated_at", "params"];

        $studentsTable = collect(Schema::connection('tenant')->getColumnListing('students'));

        $fields = [];
        foreach ($studentsTable as $field) {
            if (!in_array($field, $excludes)) {
                $fields[$field] = Str::title(str_replace('_', ' ', $field));
            }
        }
        $defaultFields =  [
            'stage'     => 'Contact Type',
            'campus'    => 'Campus',
        ];

        return $fields +  $defaultFields  + $customFields;

    }
    public static function getStudentApplications()
    {
        $student = Auth::guard('student')->user();
        $submissions = $student->submissions()->pluck('application_id');

        $studentApplications = Application::whereIN('id', $submissions)->orWhere('published', true)->student()->with([
            'invoices' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'invoices.status',
            'submissions' => function ($query) use ($student) {
                $query->where('student_id', $student->id)->orderBy('id', 'DESC');
            },
        ]);

        if($studentCampuses = $student->campuses()->pluck('campuses.id')->toArray())
        {
            $studentApplications  = $studentApplications->whereHas('campuses', function ($query) use ($studentCampuses) {
                    $query->whereIn('campuses.id', $studentCampuses);
            });
        }
        return $studentApplications->get();
    }


    public static function hasApplicationsToSubmit()
    {
        $studentApplications = self::getStudentApplications();

        $list = collect();

        foreach ($studentApplications as $application) {

            if(!($application->status) || (isset($application->properties['multiple_submissions']) && $application->properties['multiple_submissions'] == 1)){
                $list->push($application);
            }
        }
        return $list->count();
    }
}
