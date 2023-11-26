<?php

namespace App\Http\Controllers\Tenant\School\Agent;

use App\Http\Controllers\Controller;
use App\Rules\School\AgentExist;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function edit()
    {
        $agent = Auth::guard('agent')->user();
        $agency = $agent->agency()->first();

        return view('front.agent.account.index', compact('agent', 'agency'));
    }

    public function update(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $rules = [
            'first_name'    => 'required|string',
            'email'         => ['required', 'email', new AgentExist($agent->email)],
        ];

        $data = ['first_ name', 'last_name', 'email', 'phone'];

        if ($request->has('password') && ! empty(trim($request->password))) {
            $rules['passowrd'] = 'string|min:6|confirmed';
            $data[] = 'password';
        }

        $this->validate($request, $rules);

        // Valid Hash the password
        $request->merge(['password' =>   Hash::make($request->password)]);

        $agent->update($request->only($data));

        return  redirect()->back()->withSuccess('Updated Successfuly');
    }
}
