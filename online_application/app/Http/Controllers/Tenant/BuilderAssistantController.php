<?php

namespace App\Http\Controllers\Tenant;

use Storage;
use Response;
use App\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Program;
use App\Tenant\Models\Assistant;
use App\Tenant\Models\Application;
use App\Tenant\Traits\HasCampuses;
use App\Http\Controllers\Controller;
use App\Tenant\Models\AssistantBuilder;
use App\Helpers\Permission\PermissionHelpers;

class BuilderAssistantController extends Controller
{
    use  HasCampuses;
    const  PERMISSION_BASE = "application";

    public function __construct()
    {
        $this->middleware('plan.features:virtual_assistant');
    }

    public function index()
    {
       // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);


        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }
        $params = [
            'modelName' => AssistantBuilder::getModelName(),
        ];
        $assistantBuilders = AssistantBuilder::all();
        $school = School::byUuid(session('tenant'))->firstOrFail();

        return view('back.recruitment_assistant.index', compact('assistantBuilders', 'params', 'school'));
    }

    public function create()
    {
       $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $campuses = Campus::get()->pluck('title', 'id')->toArray();
        $courses = Course::get()->pluck('title', 'id')->toArray();
        $programs = Program::get()->pluck('title', 'id')->toArray();

        $applications = Application::get()->pluck('title', 'id')->toArray();

        $sections = [
            ['header' => 'Campus', 'block' => 'campus'],
            ['header' => 'Programs', 'block' => 'program'],
            ['header' => 'Courses', 'block' => 'course'],
            ['header' => 'Financial Aid', 'block' => 'financial'],
        ];

        return view(
            'back.recruitment_assistant.create',
            compact('applications', 'campuses', 'programs', 'courses', 'sections')
        );
    }

    public function store(Request $request)
    {
        $assistantBuilder = new AssistantBuilder();
        $assistantBuilder->title = $request->title;
        $assistantBuilder->slug = Str::slug($request->title);
        $assistantBuilder->description = $request->description;
        $assistantBuilder->help_title = $request->help_title;
        $assistantBuilder->help_content = $request->help_content;

        foreach ($request->files as $name => $file) {
            $uploaded['path'] = Storage::putFile('/'.session('tenant'), $request->file($name));
            $uploaded['name'] = $request->file($name)->getClientOriginalName();
        }

        if (! empty($uploaded)) {
            $assistantBuilder->help_logo = $uploaded;
        }

        $assistantBuilder->properties = $request->properties;
        $assistantBuilder->application()->associate($request->application);
        $assistantBuilder->save();

        return redirect(route('assistantsBuilder.index'));
    }

    public function edit($assistantBuilder)
    {
         if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $assistantBuilder)) {
            return PermissionHelpers::accessDenied();
        }
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['campusesEdit|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }

        $campuses = Campus::get()->pluck('title', 'id')->toArray();
        $courses = Course::get()->pluck('title', 'id')->toArray();
        $programs = Program::get()->pluck('title', 'id')->toArray();

        $applications = Application::get()->pluck('title', 'id')->toArray();

        $sections = [
            ['header' => 'Campus', 'block' => 'campus'],
            ['header' => 'Programs', 'block' => 'program'],
            ['header' => 'Courses', 'block' => 'course'],
            ['header' => 'Financial Aid', 'block' => 'financial'],
        ];

        return view(
            'back.recruitment_assistant.edit',
            compact('applications', 'campuses', 'programs', 'courses', 'sections', 'assistantBuilder')
        );
    }

    public function update(Request $request, $assistantBuilder)
    {
        $assistantBuilder = AssistantBuilder::where('slug', $assistantBuilder)->first();
        //dd($request);
        $data = $request->only(['title', 'application', 'help_title', 'help_content', 'properties']);

        foreach ($request->files as $name => $file) {
            $uploaded[$name]['path'] = Storage::putFile('/'.session('tenant'), $request->file($name));
            $uploaded[$name]['name'] = $request->file($name)->getClientOriginalName();
        }

        if (! empty($uploaded)) {
            $data = $data + $uploaded;
        }
        $assistantBuilder->update($data);

        return redirect(route('assistantsBuilder.index'));
    }

    public function destroy($assistant_builder_id)
    {
        $assistant_builder = AssistantBuilder::findOrFail($assistant_builder_id);

        if ($response = $assistant_builder->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $assistant_builder->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    //public function listAssistantsRequestedByMailWithoutApplication(Request $request)
    public function listAssistantsRequestedByMailWithoutApplication()
    {
        $assistants_collection = Assistant::where('user_id', 'regexp', '^(?=.*[a-z0-9]{2}).*[a-z]{2}')->get();
        $bookings_collection = Booking::where('user_id', 'regexp', '^(?=.*[a-z0-9]{2}).*[a-z]{2}')->get();

        $leads = $assistants_collection->merge($bookings_collection)->sortByDesc('updated_at');

        return view(
            'back.recruitment_assistant.mailRequestedStudents.index',
            compact('leads')
        );
    }

    public function destroyAssistantsRequestedByMailWithoutApplication($lead_id, $type)
    {
        switch ($type) {
            case 'quote':
                $lead = Booking::findOrFail($lead_id);
                break;
            case 'vaa':
                $lead = Assistant::findOrFail($lead_id);
                break;
            default:
                return Response::json([
                    'status'    => 404,
                    'response'  => 'Incorrect type',
                ]);
        }
        $lead->delete();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['removedId' => $lead->id.'-'.$type],
        ]);
    }

    public function show(Request $request, Assistant $assistant)
    {
        return view('back.recruitment_assistant.show', compact('assistant'));
    }

    public function quoteShow(Booking $booking)
    {
        return view('back.quotation.show', compact('booking'));
    }
}
