<?php

namespace App\Http\Controllers\Tenant;

use Auth;
use Cache;
use Response;
use App\School;
use App\Tenant\Models\Agent;
use Illuminate\Http\Request;
use App\Tenant\Models\Agency;
use App\Exports\AgenciesExport;
use App\Tenant\Traits\Integratable;
use App\Helpers\School\AgentHelpers;
use App\Helpers\School\SchoolHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\School\AgencyHelpers;
use App\Rules\Tenant\Agencies\UniqueAgency;
use App\Events\Tenant\Agency\AgencyIsCreated;
use App\Events\Tenant\Agency\AgencyIsApproved;
use App\Events\Tenant\Agent\AgentsAddedToAgency;
use App\Events\Tenant\Agent\ActivationEmailRequested;

class AgencyController extends Controller
{
    use Integratable;

    public function __construct()
    {
        $this->middleware('plan.features:agency');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = SchoolHelper::settings();
        $remoteAgencies = null;
        $params = [
            'modelName' => Agency::getModelName(),

        ];

        $isMautic = (request('source') == 'mautic');

        if (
            isset($settings['agencies']['load_mautic_agencies']) &&
            $settings['agencies']['load_mautic_agencies'] == 'Yes' && $isMautic
        ) {
            if ($integration = $this->inetgration()) {
                $remoteAgencies = Cache::remember(
                    'remoteAgencies-'.session('tenant'),
                    600,
                    function () use ($integration) {
                        return $integration->getAgencies();
                    }
                );
                if (isset($remoteAgencies['companies'])) {
                    $params['remoteAgencies'] = $remoteAgencies['companies'];
                }
            }
        }

        if (! $isMautic) {
            $agencies = Agency::all();
        } else {
            $agencies = null;
        }

        return view('back.agencies.index', compact('agencies', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.agencies.create');
    }

    /**
     * Store a newly created Agency in storage.
     */
    public function store(Request $request)
    {
        $school = School::byUuid(session('tenant'))->first();

        $request->validate([
            'name'  => ['required', 'max:255', new UniqueAgency()],
            'email' => ['required', 'email', new UniqueAgency()],
        ]);
        $agency = Agency::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'country'       => $request->country,
            'city'          => $request->city,
            'description'   => $request->description,
            'approved'      => true,
        ]);

        // Dispatch Agency Is Created Event
        event(new AgencyIsCreated($agency));

        // $integration = $this->inetgration();
        if (count(array_filter($request->agents_emails))) {
            event(new AgentsAddedToAgency(
                $agency,
                array_combine($request->agents_emails, $request->agents_names),
                $school
            ));
        }

        return redirect()->route('agencies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agency $agency)
    {
        $agency = $agency->load(
            'agents',
            'agents.students',
            'students.submissions',
            'students.submissions.application'
        );

        $fullAddress = (isset($agency->address)) ? $agency->address.', '.$agency->city : false;

        $map = AgencyHelpers::getMap($fullAddress.' '.$agency->country);

        $flag = AgencyHelpers::getFlag($agency->country);

        $roles = AgentHelpers::existingsRoles();

        return view('back.agencies.new-show', compact('agency', 'fullAddress', 'map', 'flag', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agency $agency)
    {
        return view('back.agencies.edit', compact('agency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agency $agency)
    {
        $agency->name = $request->name;
        $agency->phone = $request->phone;
        $agency->email = $request->email;
        $agency->country = $request->country;
        $agency->city = $request->city;
        $agency->description = $request->description;

        $agency->save();

        return redirect(route('agencies.index'))->withSuccess(
            'Agency successfully updated'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agency $agency)
    {
        $agents = $agency->agents();
        if ($response = $agency->delete()) {
            $deleteAgents = $agents->delete();

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $agency->id],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 404,
                    'response' => $response,
                ]
            );
        }
    }

    public function getAgenciesList(Request $request)
    {
        //Get Agency by Name or Email

        $agency = Agency::where('name', 'like', '%'.$request->q.'%')->orWhere('email', 'like', '%'.$request->q.'%')->get()->toArray();

        //return $agency;

        //$agency = ['test2'=>'testOn' , 'test'=>'testTwo'];
        return Response::json($agency);
    }

    public function resendActivationEmail($payload)
    {
        if (! isset($payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        //get Agent
        $agent = Agent::find($payload['agentId']);

        $school = School::byUuid(session('tenant'))->first();

        event(new ActivationEmailRequested($agent, $school));

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    public function inviteAgents(Request $request, Agency $agency)
    {
        $school = School::byUuid(session('tenant'))->first();

        //invite agents only
        if ($request->has('action') && $request->action == 'invite_agents') {
            // $integration = $this->inetgration();
            if (count(array_filter($request->agents_emails))) {
                foreach ($request->agents_emails as $agent_email) {
                    $agent = Agent::where('email',$agent_email)->first();
                    if(isset($agent)) {
                        return redirect()->back()->with('error', "{$agent_email} cannot be added, Agent already exists.");
                    }
                }
                event(new AgentsAddedToAgency(
                        $agency,
                        array_combine($request->agents_emails, $request->agents_names),
                        $school
                    )
                );
            }
        }

        return redirect()->back()->withSuccess(
            "Agents invited successfully, they would receive an email to join {$agency->name}"
        );
    }

    public function toggleIsAdmin($payload)
    {
        if (! isset($payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        //get Agent
        $agent = Agent::find($payload['agentId']);
        $agency = $agent->agency()->first();
        $user = Auth::guard('agent')->user();

        $agent->is_admin = ! $agent->is_admin;
        $agent->save();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    /**
     * Delete agent from the Agency
     */
    public function deleteAgent($payload)
    {
        if (! isset($payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        //get Agent
        $agent = Agent::find($payload['agentId']);
        $agency = $agent->agency()->first();
        $user = Auth::guard('agent')->user();
        $agent->delete();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    /**
     * Toogle Agency Approved
     */
    public function toggleStatus($payload)
    {
        if (! isset($payload['agencyId'])) {
            return abort(404);
        }
        if (! $agency = Agency::find($payload['agencyId'])) {
            return abort(404);
        }
        $agency->approved = ! $agency->approved;

        if($agency->approved) {
            event(new AgencyIsApproved($agency));
        }
        if ($agency->save()){
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => [
                    'html'      => 'done',
                    'agency'    => $agency->id,
                    'status'    => $agency->approved,
                ],
            ]);
        }
    }

    public function rolPrivileges($payload)
    {
        if (! isset($payload['agentId']) || ! isset($payload['rol'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        if (! $agent = Agent::find($payload['agentId'])) {
            return Response::json([
                'status'    => 404,
                'response'  => 'Page not found',
            ]);
        }

        $agent->roles = $payload['rol'];
        $agent->save();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => 'done'],
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $agencies = Agency::whereIn('id',$request->selected);
        if ($response = $agencies->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'data_table' => 'agencies_index_table',
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

    public function agenciesDownloadExcel(Request $request)
    {
        $file = $request->file;
        $agenciesIds = $request->data;
        $agenciesIds = explode(',', $agenciesIds);
        $data = [];

        $agencies = Agency::whereIn('id', $agenciesIds)->get();
        foreach ($agencies as $agency) {
            $agencyData = [];
            $agencyData['name'] = $agency->name;
            $agencyData['email'] = $agency->email;
            $agencyData['phone'] = $agency->phone;
            $agencyData['n_students'] = $agency->students()->count();
            $agencyData['n_agents'] = $agency->agents()->count();
            $agencyData['status'] = $agency->approved == 1 ? 'Approved' : 'Unapproved';
            array_push($data, $agencyData);
        }

        $headings = [
            __('Name'),
            __('Email'),
            __('Phone'),
            __('Number of Students'),
            __('Number of Agents'),
            __('Status'),
        ];

        $export = new AgenciesExport($data, $headings);

        $file_name = 'agencies_' . time() . '.xlsx';

        if ($file === 'csv') {
            $file_name = 'agencies_' . time() . '.csv';
        }

        return Excel::download($export, $file_name);
    }
}
