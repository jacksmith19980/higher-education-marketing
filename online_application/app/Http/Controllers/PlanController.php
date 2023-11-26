<?php

namespace App\Http\Controllers;

use App\Plan;
use Auth;
use Illuminate\Http\Request;
use Response;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $params = [
            'modelName' => Plan::getModelName(),
        ];

        $plans = Plan::all();

        return view('back.plans.index', compact('plans', 'params'));
    }

    public function create()
    {
        return view('back.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'title'        => 'required|unique:plans|max:255',
                'price'        => 'required|numeric',
                'features'     => 'required',
            ]
        );

        if (! $request->trial_period) {
            $request->merge(['trial_period' => 0]);
        }

        $plan = Plan::create(
            $request->only(
                'title',
                'price',
                'trial_period',
                'is_active',
                'features_description',
                'short_description',
                'features'
            )
        );

        $plan->properties = $request->properties;
        $plan->save();

        return redirect()->route('plans.index')->with('Plan {$plan->title } created successfully!');
    }

    public function edit(Plan $plan)
    {
        return view('back.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate(
            [
                'title'        => 'required|max:255|unique:plans,title,'.$plan->id,
                'price'        => 'required|numeric',
                'features'     => 'required',
            ]
        );

        if (! $request->trial_period) {
            $request->merge(['trial_period' => 0]);
        }

        $plan->update(
            $request->only(
                'title',
                'price',
                'trial_period',
                'is_active',
                'features_description',
                'short_description',
                'features'
            )
        );

        $plan->properties = $request->properties;
        $plan->save();

        return redirect(route('plans.index'))->with('Plan {$plan->title } updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        if ($response = $plan->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $plan->id],
                ]
            );
        } else {
            return Response::json(['status' => 404, 'response' => $response]);
        }
    }

    public function select()
    {
        $plans = Plan::where([['is_active', 1], ['is_public', 1]])->get();

        return view('back.plans.select_plan', compact('plans'));
    }

    public function selected(Plan $plan)
    {
        return redirect(route('schools.create', ['plan' => $plan]));
    }
}
