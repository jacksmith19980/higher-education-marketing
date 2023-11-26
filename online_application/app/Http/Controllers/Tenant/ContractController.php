<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Contract;
use App\Tenant\Models\Envelope;
use App\Tenant\Models\Field;
use App\Tenant\Models\Student;
use Illuminate\Http\Request;
use Response;
use Sign;

class ContractController extends Controller
{
    public function list(Contract $contract, Request $request)
    {
        $list = Sign::envelopeDocumentsList($contract);
        $html = view("back.students.esignature.$contract->service.document-list", compact('list', 'contract'))->render();
        return $html;
    }

    public function downloadContractDocument($payload)
    {
        return Sign::getDownloadLink($payload);
    }

    public function reviewAndSendContract($payload)
    {
        $contract = Contract::find($payload['contractId']);

        $response = Sign::reviewEnvelope($contract->uid, $contract->student->id);

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => $response,
        ]);
    }

    public function voidContract($payload)
    {
        $contract = Contract::find($payload['contractId']);
        $response = Sign::voidContract($contract);

        if (isset($response['esignature']['envelopeId'])) {
            $contract->update([
                'status' => 'voided',
            ]);
            $response['message'] = __('Contract Voided Successfully!');
        }

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => $response,
        ]);
    }

    public function sendContractReminder($payload)
    {
        $contract = Contract::where('uid', $payload['uid'])->first();
        if ($contract->student->id != $payload['studentId']) {
            return null;
        }
        $response = Sign::sendSignatureReminder($contract);

        dd($response);
    }

    /**
     * For Adobe Sign Webhook verification
     *
     * @param Request $request
     * @return void
     */
    public function webhookVerification(Request $request)
    {
        if($request->header('x-adobesign-clientid') !== null){
            return response('' , 200)
            ->withHeaders([
                'X-AdobeSign-ClientId' => $request->header('x-adobesign-clientid')
            ]);
        }
    }
}
