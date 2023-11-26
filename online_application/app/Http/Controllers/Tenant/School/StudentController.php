<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Student;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Session;

class StudentController extends Controller
{
    public function profile()
    {
        session()->forget('tenant');
        $student = Auth::guard('student')->user();

        return view('front.profile.index', compact('student'));
    }

    public function update(Request $request)
    {
        // Update $student
        $student = Auth::guard('student')->user();
        //dd($request);
        $request->validate([
               'first_name' => 'required|string',
               'last_name' => 'required|string',
               'email' => 'email|unique:users,email,'.$student->id,
               'password' => 'nullable|string|min:6|confirmed',
               'address' => 'nullable|string',
               'city' => 'nullable|string',
               'postal_code' => 'nullable|string',
               'country' => 'nullable|string',
        ]);

        if (isset($request->password)) {
            $res = $student->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'address'       => $request->address,
                'city'          => $request->city,
                'postal_code'   => $request->postal_code,
                'country'       => $request->country,
            ]);
        } else {
            $res = $student->update([
                'first_name'     => $request->first_name,
                'last_name'      => $request->last_name,
                'email'          => $request->email,
                'address'        => $request->address,
                'city'           => $request->city,
                'postal_code'    => $request->postal_code,
                'country'        => $request->country,
            ]);
        }

        return redirect()->back()->withSuccess('Your profile updated successfully');
    }
}
