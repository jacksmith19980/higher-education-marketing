<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Permission\PermissionHelpers;
use App\Http\Requests\Tenant\EducationalServiceCatrgoryRequest;
use App\Http\Requests\ServicesRequest;
use App\Tenant\Models\EducationalService;
use App\Tenant\Models\EducationalServiceCategory;
use Response;

class EducationalServiceCategoriesController extends Controller
{

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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return EducationalServiceCategory::where('is_published', true)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EducationalServiceCatrgoryRequest $request)
    {
        $newEducationalServiceCategory = EducationalServiceCategory::create($request->all());

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                    'name' => $newEducationalServiceCategory->name,
                    'value' => $newEducationalServiceCategory->id
                ],
            ]
        );
    }
}
