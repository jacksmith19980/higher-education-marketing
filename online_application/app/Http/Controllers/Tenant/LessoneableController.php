<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Lessoneable;
use App\Helpers\Application\ProgramHelpers;
use App\Helpers\School\CampusHelpers;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\GroupHelpers;
use App\Helpers\School\ModelHelpers;
use App\Helpers\School\SemesterHelpers;
use App\Helpers\School\StudentHelpers;
use App\Http\Controllers\Tenants\GroupController;
use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Repositories\Interfaces\InstructorRepositoryInterface;
use App\Tenant\Models\Group;
use App\Tenant\Models\Program;
use DebugBar\DebugBar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class LessoneableController extends Controller
{

    public function index()
    {
        $params = [
            'modelName' => Lessoneable::getModelName(),
        ];
        $lessoneables = Lessoneable::all();

        return view('back.lessoneable.index', compact('lessoneables', 'params'));
    }

    
	  public static function getSelectedGroupsInArrayOnlyTitleId($data = null)
    {
        if ($data) {
          return Arr::pluck($data, 'title' , 'id');
        } else {
          return Arr::pluck(Lessoneable::select('title' , 'id')->get()->toArray(), 'title' , 'id');
        }
    }
	
    // Get Lessoneables by Lesson Id 
    public static function getLessoneableByLessonId($id, $payload)
    { 
        foreach ($payload as $lessoneable) 
        {
          if ($lessoneable['lesson_id'] == $id) 
          {
            $lessoneable_selected[] = [
              'id' 				=> $lessoneable->id,
              'lesson_id'			=> $lessoneable->lesson_id,
              'lessoneable_type' 	=> $lessoneable->lessoneable_type,
              'lessoneable_id' 	=> $lessoneable->lessoneable_id,
              'created_at' 		=> $lessoneable->created_at,
              'updated_at' 		=> $lessoneable->updated_at
            ];
          }
        }
        return $lessoneable_selected;       
    }

    // Merge lessoneable and group table - display group name through lessonable_id
    public static function getGroupNameByLessoneableId($payload)
	  {
        if (is_array($payload)) {
            foreach ($payload as $lessoneable) {					
				$group = Group::findOrFail($lessoneable['lessoneable_id']);
				$groups_lessons[] = [
					'id' => $group->id,
					'title' => $group->title
				];
            }
        } else {
            //$groups_lessons = Group::findOrFail($payload['id'])->first()->groups;
        }

        return $groups_lessons;
    }
}
