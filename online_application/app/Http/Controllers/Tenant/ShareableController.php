<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Permission\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Shareable;
use Illuminate\Http\Request;
use Auth;
use Response;

class ShareableController extends Controller
{
    const  PERMISSION_BASE = "document";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $student = Auth::guard('student')->user();

        $shareables = Shareable::where('shareable_type', 'App\Tenant\Models\Student')->where('shareable_id', $student->id)->orderBy('id', 'DESC')->get();

        $params = [
            'modelName' => 'documents',
        ];

        return view('back.shareables.index', compact('shareables', 'permissions', 'params', 'student'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shareable  $shareable
     * @return \Illuminate\Http\Response
     */
    public function show(Shareable $shareable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shareable  $shareable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shareable $shareable)
    {
        //
    }

}
