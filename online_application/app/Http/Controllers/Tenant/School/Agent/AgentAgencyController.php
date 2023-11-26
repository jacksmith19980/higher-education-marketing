<?php

namespace App\Http\Controllers\Tenant\School\Agent;

use App\Events\Tenant\Agency\AgencyIsUpdated;
use App\Events\Tenant\Agent\ActivationEmailRequested;
use App\Events\Tenant\Agent\AgentsAddedToAgency;
use App\Events\Tenant\Agent\InvitationEmailRequested;
use App\Helpers\School\AgentHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Agency;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Application;
use Auth;
use Illuminate\Http\Request;
use Response;

class AgentAgencyController extends Controller
{


    public function edit($school, Agency $agency)
    {
        $agent = Auth::guard('agent')->user();

        //Authorize
        $this->isAuthorized($agent, $agency);

        $agents = $agent->agency()->first()->agents()->orderBy('id', 'DESC')->get();
        $agencyApplications = Application::agency()->with([
                'sections',
                'sections.fields',
                'agencySubmissions' => function ($q) use ($agency) {
                    return $q->where('agency_id', $agency->id);
                },
            ])->get();
        $applicationsList = [];
        foreach ($agencyApplications as $application) {
            if ($application->properties['show_in'] == 'settings') {
                $applicationsList[] = $application;
            }
        }

        $roles = AgentHelpers::existingsRoles($agent->roles);

        return view('front.agent.agency.edit', compact('agency', 'agents', 'applicationsList', 'roles'));
    }

    public function update(Request $request, $school, Agency $agency)
    {
        $agent = Auth::guard('agent')->user();
        $school = School::bySlug($request->school)->first();

        // Get invited agents details
        if (isset($request->agents_emails)) {
            if (count(array_filter($request->agents_emails))) {
                $agentsDetails = array_combine($request->agents_emails, $request->agents_names);
            }
        }

        // Invite Agents Only
        if (isset($request->action) && $request->action == 'invite_agents') {
            if (! empty($agentsDetails)) {
                $this->inviteAgents($agency, $agentsDetails, $school);

                return redirect(route('school.agent.agency.edit', ['school' => $school, 'agency' => $agency]));
            } else {
                return redirect()->back();
            }
        }

        //Authorize
        $this->isAuthorized($agent, $agency);

        $this->validate($request, [
            'name'          => 'required',
            'email'         => 'required|email',
            'phone'         => 'required',
            'address'       => 'required',
            'country'       => 'required',
            'city'          => 'required',
        ]);

        $agency->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'postal_code'   => $request->postal_code,
            'country'       => $request->country,
            'city'          => $request->city,
            'description'   => $request->description,
        ]);

        // Update Integration Agencies
        event(new AgencyIsUpdated($agency));

        if (! empty($agentsDetails)) {
            $this->inviteAgents($agency, $agentsDetails, $school);
        }

        return redirect(route('school.agent.home', $school));
    }

    protected function inviteAgents(Agency $agency, $agentsDetails, School $school)
    {
        event(new AgentsAddedToAgency($agency, $agentsDetails, $school));
    }

    public function resendActivationEmail(Request $request)
    {
        if (! isset($request->payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        //get Agent
        $agent = Agent::find($request->payload['agentId']);
        $agency = $agent->agency()->first();
        $user = Auth::guard('agent')->user();

        // check if the current user is allowed to perform this action
        if ($agency->id != $user->agency()->first()->id || ! $user->is_admin) {
            return Response::json([
                'status'    => 403,
                'response'  => 'You are not allowed to make this request',
            ]);
        }
        $school = School::bySlug($request->school)->first();
        event(new InvitationEmailRequested($agent, $school, $agency));

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    public function toggleIsAdmin(Request $request)
    {
        if (! isset($request->payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        //get Agent
        $agent = Agent::find($request->payload['agentId']);
        $agency = $agent->agency()->first();
        $user = Auth::guard('agent')->user();

        // check if the current user is allowed to perform this action
        if ($agency->id != $user->agency()->first()->id || ! $user->is_admin) {
            return Response::json([
                'status'    => 403,
                'response'  => 'You are not allowed to make this request',
            ]);
        }

        $agent->is_admin = ! $agent->is_admin;
        $agent->save();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    public function deleteAgent(Request $request)
    {
        if (! isset($request->payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        //get Agent
        $agent = Agent::find($request->payload['agentId']);
        $agency = $agent->agency()->first();
        $user = Auth::guard('agent')->user();

        // check if the current user is allowed to perform this action
        if ($agency->id != $user->agency()->first()->id || ! $user->is_admin) {
            return Response::json([
                'status'    => 403,
                'response'  => 'You are not allowed to make this request',
            ]);
        }
        $agent->delete();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    protected function isAuthorized(Agent $agent, Agency $agency)
    {
        // check if agent is able to update this agency
        if (($agent->agency->id != $agency->id) || ! $agent->isAgentAdmin) {
            abort(404);
        }
    }

    public function rolPrivileges(Request $request)
    {
        if (! isset($request->payload['agentId']) || ! isset($request->payload['rol'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        if (! $agent = Agent::find($request->payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        $agent->roles = $request->payload['rol'];
        $agent->save();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    public function checkAgencyRegistration($request) {
        $school = $request->school;

        // Select unapproved agencies by email address
        $agency = Agency::where('email' , $request->email)->where('approved' , false)->first();

        $html = '';
        // if the agency is already registered
        if($agency){

            // if the school has Agency applications to complete the registration
            if(Application::byObject('agency')->where('properties' ,'LIKE','%"show_in":"registration"%')->count()){
                $html = view('front.agent.auth.registered-messgae' , compact('agency' , 'school'))->render();
            }
        }
        return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'html' => $html
                ],
            ]);
    }

}
