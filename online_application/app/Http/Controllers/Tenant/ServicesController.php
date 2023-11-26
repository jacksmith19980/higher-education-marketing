<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Permission\PermissionHelpers;
use App\Http\Requests\Tenant\ServicesRequest;
use App\Tenant\Models\EducationalService;
use App\Tenant\Models\EducationalServiceCategory;
use Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServicesController extends Controller
{

    const  PERMISSION_BASE = "service";

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE,
            'delete|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $educationalServices = EducationalService::orderBy('id', 'desc')->get();
        $educationalServiceCategories = EducationalServiceCategory::all();

        $params = [
            'modelName' => 'services',
        ];

        return view('back.services.index', compact('educationalServiceCategories', 'educationalServices', 'permissions', 'params'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
    }

    /**
     * Store New User
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function codeValidator(Request $request) : JsonResponse
    {
        $service = EducationalService::where('code', $request->email)->first();

        if($service){
            return Response::json([
                'status'    => 400,
                'response'  => 'error',
                'message'  => 'Code already exists'
            ]);
        }

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $educationalServiceCategories = EducationalServiceCategory::pluck('name', 'id')->all();
        $uid = rand();

        return view('back.services.create', compact('educationalServiceCategories', 'uid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServicesRequest $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        EducationalService::create([
            'name' => $request->service_name,
            'code' => $request->service_code,
            'description' => $request->service_description,
            'amount' => $request->amount,
            'educational_service_categories_id' => $request->category,
        ]);

        return redirect(route('services.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EducationalService $service)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }

        $educationalServiceCategories = EducationalServiceCategory::pluck('name', 'id')->all();
        $uid = rand();

        return view('back.services.edit', compact('educationalServiceCategories', 'service', 'uid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServicesRequest $request, EducationalService $service)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $service->update([
            'name' => $request->service_name,
            'code' => $request->service_code,
            'description' => $request->service_description,
            'amount' => $request->amount,
            'educational_service_categories_id' => $request->category,
        ]);

        return redirect(route('services.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EducationalService  $educationalservice
     * @return \Illuminate\Http\Response
     */
    public function destroy(EducationalService $service)
    {

        $response = $service->delete();

        if ($response) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $service->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function bulkDestroy(Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', null)) {
            return PermissionHelpers::accessDenied();
        }

        $educationalServices = [];
        if(isset($request->selected) and isset($request->selected[0]['selected'])){
            $educationalServices = EducationalService::whereIn('id', array_map(fn($a) => $a['selected'], $request->selected));
        }else{
            $educationalServices = EducationalService::whereIn('id',$request->selected);
        }

        if ($educationalServices->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'data_table' => 'students_datatable',
                        'message'    => __('Deleted Successfully!')
                    ],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 419,
                    'response' => 'faild',
                    'extra'    => ['message' => 'Something went wrong!'],
                ]
            );
        }
    }
}
