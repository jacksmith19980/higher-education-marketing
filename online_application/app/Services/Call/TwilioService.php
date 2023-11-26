<?php

namespace App\Services\Call;

use App\School;
use App\Tenant\Models\Admission;
use App\Tenant\Models\Plugin;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class TwilioService
{
    protected $client;
    protected $credentials;

    // Get Twilio Credentials
    protected function credentials()
    {
        $plugin = Plugin::where('name', 'twilio')->first();

        return $this->credentials = $plugin->properties;
    }

    // Get Twilio Client
    protected function client()
    {
        $credentials = $this->credentials();
        $this->client = new Client($credentials['account_sid'], $credentials['auth_token']);

        return $this->client;
    }

    protected function getConferenceName($request, $admission)
    {
        return
        str_replace(' ', '_', $request['name']).'_'.
        str_replace(' ', '_', $request['phone']).'_'.
        str_replace(' ', '_', $admission->name).'_'.
        time();
    }

    /**
     * Make a Phone Call
     */
    public function call(Admission $admission, array $request)
    {
        $client = $this->client();

        $school = School::where('slug', request()->school)->firstorFail();

        $conferenceName = $this->getConferenceName($request, $admission);

        $phone = explode(',', $admission->phone);
        $phone = $phone[array_rand($phone, 1)];

        $call = $client->calls->create(
            trim($phone), // Call Admission First
            $this->credentials['call_from'], // From a valid Twilio number
            [
                'statusCallback' => 'http://appstage.crmforschools.net/webhook/atc/call/response',

                'statusCallbackMethod' => 'POST',

                'timeout'   =>'20',

                'twiml'     => "<Response>
                                <Say voice='Alice'>Incoming Call Request from  ".$request['name'].'</Say>
                                <Dial>
                                <Conference>'.$conferenceName.'</Conference>
                                </Dial>

                            </Response>',
            ]
        );
        /* <Dial>".$request['phone']."</Dial> */
        return ['call' => $call, 'conferenceName' =>$conferenceName];
    }

    public function joinConference($request)
    {
        $client = $this->client();
        $conference = $client->conferences($request->conference);
        $participants = $conference->participants;
        $participants->create($this->credentials['call_from'], $request->phone);

        return $conference;
    }

    public function status($request)
    {
        $client = $this->client();

        if ($request->has('callSid')) {
            $call = $client->calls($request->callSid)->fetch();

            return $call->status;
        }
        if ($request->has('conferenceName')) {
            $conference = $client->conferences->read([
                'status'        => 'in-progress',
                'friendlyName'  => $request->conferenceName,
            ]);

            if (! empty($conference)) {
                if ($sid = $conference[0]->sid) {
                    return $conference[0];
                }
            }
        }

        return null;

        //$call = $client->calls($callSid)->fetch();

        /* $call = $client->calls($callSid)->fetch();


        $call->update([
                "twiml" => "
                <Response>
                    <Dial>
                        +15145669079
                    </Dial>
                    </Response>"
            ]
        );
 */
        //if($call->status == 'in-progress'){
            /* $client->calls($callSid)->update([
                        "twiml" => "<Response><Dial>+15145669079</Dial></Response>"
                    ]
            ); */
        //}
        /* dump($call); */
        //return $call->status;
    }
}
