<?php

namespace App\Http\Controllers\Tenant\School;

use App\Events\Tenant\Assistant\FollowUpRequested;
use App\Events\Tenant\Assistant\FollowUpScheduled;
use App\Events\Tenant\Assistant\FollowUpUpdate;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Admission;
use Call;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;

class CallBackController extends Controller
{
    /**
     * Show Request Call Back Form
     */
    public function show(Request $request, $school, $assistantBuilder, $step)
    {
        return view('front.recruitment_assistant._partials.call-back.form', compact('school', 'assistantBuilder', 'step'));
    }

    /**
     * Request Call Back
     */
    public function request(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'phone'     => 'required',
            'consent'   => 'required',
        ]);

        // Find Admission and Status
        list($admission, $status) = $this->getAdmission();

        $request = $request->all();
        $request['admission'] = $admission;
        $request['status'] = $status;

        event(new FollowUpRequested('call', $request));

        // Make the Phone Call
        if ($status == 'pending') {
            $response = Call::call($admission, $request);
        } elseif ($status == 'scheduled') {
            event(new FollowUpScheduled('call', $request));
        }

        $html = view('front.recruitment_assistant._partials.call-back.status', compact('admission', 'status'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                'html'      => $html,
                'status'    => $status,
                'call_sid'  => isset($response['call']) ? $response['call']->sid : null,
                'conferenceName'  => isset($response['conferenceName']) ? $response['conferenceName'] : null,
            ],
        ]);
    }

    /**
     * Add Participant to conference
     */
    public function add(Request $request)
    {
        $conference = Call::joinConference($request);

        if ($conference) {
            event(new FollowUpUpdate('call', $request, 'initiated'));
        }

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                'conference'    => $conference,
            ],
        ]);
    }

    public function status(Request $request)
    {
        if ($request->has('callSid')) {
            $status = Call::status($request);

            if ($status == 'completed') {
                event(new FollowUpUpdate('call', $request, 'completed'));
            }

            return Response::json([
            'status'    => 200,
            'response'  => 'success',
                'extra'     => [
                    'status'    => $this->getStatus($status, false),
                    'call_sid'  => $request->callSid,
                ],
            ]);
        }

        if ($request->has('conferenceName')) {
            if ($conference = Call::status($request)) {
                return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => [
                        'conference_sid'  => $conference->sid,
                        ],
                        ]);
            }
        }
    }

    protected function getStatus($status = null, $isAdvisor = false)
    {
        if ($isAdvisor) {
        } else {
            switch ($status) {
                case 'queued':
                case 'ringing':
                    return 'Connecting you to our advisors!';
                    break;

                case 'in-progress':
                    return 'In Progress';
                    break;

                case 'completed':
                    return 'Completed';
                    break;

                default:
                    return $status;
                break;
            }
        }
    }

    /**
     * Get Admission Person and Initial Call Status
     */
    protected function getAdmission()
    {
        // Get All Availalble Advisors
        $admissions = $this->getAvailableAdvisors();

        if (! $admissions) {
            return [
                $this->getTheLucky(null),
                'scheduled',
            ];
        }

        return [
            $this->getTheLucky($admissions),
            'pending',
        ];
    }

    protected function getTheLucky($admissions = null)
    {
        // No One is Available
        if (! $admissions) {
            $admissions = Admission::where('available', true)->withCount(['followups' => function ($query) {
                $query->where('created_at', '>=', Carbon::today());
            }])->get();
        }

        // If One Advisor only is available
        if ($admissions->count() == 1) {
            return $admissions->first();
        }

        // get the min count of the follow up request for the avaialable advisors
        if ($advisors = $admissions->pluck('followups_count', 'id')->toArray()) {
            if ($advisor_id = array_keys($advisors, min($advisors))[0]) {
                return Admission::find($advisor_id);
            }
        } else {
            // No Advisor Avialable // get First One in DB
            return Admission::first();
        }
    }

    /**
     * Get All Available Admissions
     */
    protected function getAvailableAdvisors()
    {
        $admissions = Admission::where('available', true)->withCount(['followups' => function ($query) {
            $query->where('created_at', '>=', Carbon::today());
        }])->get();

        $available = $admissions->filter(function ($admission) {
            $now = Carbon::now()->timezone($admission->timezone);

            $today = $now->format('l');
            $time = $now->format('H:i');

            if (
                in_array($today, array_keys($admission->availability))
                && $this->between($admission->availability[$today]['start'], $admission->availability[$today]['end'], $time)
            ) {
                return $admission;
            }
        });

        if ($available->count()) {
            return $available;
        }

        return null;
    }

    /**
     * Compare if the time is between the start and end date time
     */
    protected function between($start, $end, $time)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        $time = strtotime($time);

        return $time > $start && $time < $end;
    }
}
