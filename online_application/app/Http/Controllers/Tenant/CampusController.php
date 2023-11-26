<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Permission\PermissionHelpers;
use App\Helpers\Plan\PlanHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Response;
use Spatie\Permission\Models\Permission;
use Storage;
use App\Tenant\Traits\HasCampuses;


class CampusController extends Controller
{
    use  HasCampuses;

    const PERMISSION_BASE = "campus";

    protected $campusRepository;

    public function __construct(CampusRepositoryInterface $campusRepository)
    {
//        $this->middleware('plan.features:application')
//            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        $this->campusRepository = $campusRepository;
    }

    public function index()
    {
         // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if(!PermissionHelpers::checkActionPermission( self::PERMISSION_BASE, 'view', null))
        {
            return PermissionHelpers::accessDenied();
        }


        $params = [
            'modelName'   => Campus::getModelName(),
        ];

        $campuses = $this->campusRepository->all();
        $campuses->load('classrooms');

        return view('back.campuses.index', compact('campuses', 'params'));
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        return view('back.campuses.create');
    }

    public function store(Request $request)
    {
         if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $request->validate([
            'title' => 'required',
        ]);

        $campus = $this->campusRepository->create([
              'title'      => $request->title,
              'slug'       => $request->filled('slug') ? $request->slug : Str::slug($request->title),
              'properties' => [],
        ]);
        //$campus->properties = $this->getPropiertiesValues($request);
        $campus->properties = $request->properties;

        $uploaded = [];
        if (count($request->files) > 0) {
            $uploaded['path'] = Storage::putFile('/'.session('tenant'), $request->file('featured_image'));
            $uploaded['name'] = $request->file('featured_image')->getClientOriginalName();
        }

        if (isset($request->details)) {
            $campus->details = $request->details;
        }

        if (count($uploaded) > 0) {
            $properties = $request->properties;
            $properties['featured_image'] = $uploaded;
            $campus->properties = $properties;
        }

        $campus->save();

        $message = ['success' => "Campus {$campus->title } created successfully!"];

        if (Application::all()->count() > 0) {
            $message['warning'] = 'You need refresh campus lists in your applications to keep updated';
        }

        return redirect(route('campuses.index'))
            ->with($message);
    }

    public function show($id)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!$permissions['view|' . self::PERMISSION_BASE]) {
            return redirect(route(PermissionHelpers::REDIRECTIO_ON_FAIL));
        }
    }

    public function edit($campus_id)
    {
        $campus = $this->campusRepository->findOrFail($campus_id);


        if(!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $campus))
        {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);


        return view('back.campuses.edit', compact('campus'));
    }

    public function update(Request $request, $campus_id)
    {
        $campus = $this->campusRepository->findOrFail($campus_id);

        if(!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE , 'edit', $campus))
        {
            return PermissionHelpers::accessDenied();
        }

        $request->validate([
            'title' => 'required',
        ]);

        $input = $request->only(['title', 'slug' ,'details', 'properties']);


        $this->campusRepository->fill($campus, $input);

        $campus_properties = $campus->properties;

        if (count($request->files) > 0) {
            $uploaded = [];
            $uploaded['path'] = Storage::putFile('/'.session('tenant'), $request->file('featured_image'));
            $uploaded['name'] = $request->file('featured_image')->getClientOriginalName();

            $properties = $request->properties;
            $properties['featured_image'] = $uploaded;
            $campus->properties = $properties;
        } else {
            $properties = $campus->properties;
            $properties['featured_image'] = array_key_exists(
                'featured_image',
                $campus_properties
            ) ? $campus_properties['featured_image'] : '';
            $campus->properties = $properties;
        }
        $campus->save();

        return redirect(route('campuses.index'))
            ->with('success', "Campus {$campus->title } updated successfully!");
    }

    public function destroy($campus_id)
    {
        $campus = $this->campusRepository->findOrFail($campus_id);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $campus)) {
            return PermissionHelpers::accessDenied();
        }

        // Delete Application
        if ($response = $this->campusRepository->delete($campus)) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $campus->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    private function getPropiertiesValues(Request $request): array
    {
        $properties = [];
        $propierties_fields = ['short_description', 'campus_location', 'video', 'virtual_tour'];

        foreach ($propierties_fields as $field) {
            if (isset($request->$field)) {
                $properties[$field] = $request->$field;
            }
        }

        return $properties;
    }

    public function information(School $school, Campus $campus, Request $request)
    {
        $assistantBuilder = $request->assistantBuilder;
        $step = $request->step;

        return view(
            'front.recruitment_assistant._partials.campus.modal-information',
            compact('campus', 'school', 'assistantBuilder', 'step')
        );
    }

    public function calendar(Request $request, Campus $campus)
    {
        return view('back.campuses._partials.calendar', compact('campus'));
    }
}
