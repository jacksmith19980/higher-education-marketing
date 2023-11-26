<?php

namespace App\Http\Controllers\Tenant\School\Instructor;

use App\Http\Controllers\Controller;
use App\School;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Response;
use Session;

class HomeController extends Controller
{
    public function index(School $school, Request $request)
    {
        $schools = School::all();

        return view(
            'front.instructor.dashboard.index',
            compact('school', 'schools')
        );
    }

    public function profile(School $school)
    {
        $instructor = Auth::guard('instructor')->user();

        return view(
            'front.instructor.dashboard.profile',
            compact('school', 'instructor')
        );
    }

    public function update(Request $request)
    {
        $instructor = Auth::guard('instructor')->user();

        $request->validate([
               'first_name' => 'required|string',
               'last_name' => 'required|string',
               'email' => 'email|unique:users,email,'.$instructor->id,
               'phone' => 'required|string',
               'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (isset($request->password)) {
            $res = $instructor->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'password'      => Hash::make($request->password)
            ]);
        } else {
            $res = $instructor->update([
                'first_name'     => $request->first_name,
                'last_name'      => $request->last_name,
                'email'          => $request->email,
                'phone'         => $request->phone
            ]);
        }

        return redirect()->back()->withSuccess('Your profile updated successfully');
    }
}
