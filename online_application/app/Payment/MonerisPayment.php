<?php

namespace App\Payment;

use App\Payment\moneris\mpgRequest;
use App\Payment\moneris\CofInfo;
use App\Payment\moneris\mpgHttpsPost;
use App\Payment\moneris\mpgTransaction;
use App\Payment\moneris\mpgCvdInfo;
use App\Events\Tenant\Payment\InvoicePaid;

class MonerisPayment extends Payment implements PaymentInterface
{
    protected function succssResponseCode (){
        return ['000','001',"002","003","004","005","006","007","008","009","010","023","024"
        ,"025","026","027","028","029"];
    }


    protected function isSuccessfull($code)
    {

        return in_array($code, $this->succssResponseCode());
    }

    public function processPayment($request)
    {
        $storeId = $this->paymentGateway->properties['store_id'];
        $api_key = $this->paymentGateway->properties['api_key'];
        $total = $this->invoice->total;
        $isAjax = ($this->request->has('ajax')) ? true : false;
        if (is_null($this->paymentGateway->properties['is_sandbox_account'])) {
            $isSandBox = false;
        } else {
            $isSandBox = true;
        }

        $expiry = str_replace('/', "" , $this->request->cc_expiry);

        $type = 'purchase';
        $cust_id = time();
        $order_id = 'pmt-'.time();
        $amount = $total;
        $pan = $this->request->cc_number;
        $expiry_date = $expiry;
        $crypt = '7';
        $dynamic_descriptor = $this->request->cc_number;
        $status_check = 'true';
        $cvd_indicator = '1';
        $cvd_value = $this->request->cc_cvs;

        $paymentData = [
        'type' => $type,
        'order_id' => $order_id,
        'cust_id' => $cust_id,
        'amount' => $amount . '.00',
        'pan' => $pan,
        'expdate' => $expiry_date,
        'crypt_type' => $crypt,
        'dynamic_descriptor' => $dynamic_descriptor
        ];

        $txnArray = $paymentData;

        $mpgTxn = new mpgTransaction($txnArray);

        $cof = new CofInfo();
        $cof->setPaymentIndicator("U");
        $cof->setPaymentInformation("2");
        $cof->setIssuerId(time());
        $mpgTxn->setCofInfo($cof);

        $cvdTemplate = [
            'cvd_indicator' => $cvd_indicator,
            'cvd_value' => $cvd_value
        ];
        $mpgCvdInfo = new mpgCvdInfo($cvdTemplate);
        $mpgTxn->setCvdInfo($mpgCvdInfo);


        $mpgRequest = new mpgRequest($mpgTxn);

        $mpgRequest->setProcCountryCode("CA");
        $mpgRequest->setTestMode($isSandBox);


        $mpgHttpPost = new mpgHttpsPost($storeId, $api_key, $mpgRequest);

        $mpgResponse = $mpgHttpPost->getMpgResponse();

        $response = $this->processMonerisResponse($mpgResponse->getMpgResponseData($mpgResponse));

        if ($response['success']) {
            $message = 'Thank you for your payment';
        } else {
            $message = $response['Message'];
        }

        return $this->processResponse((bool) $response['success'], $isAjax, $message, $mpgResponse);
    }

    protected function processMonerisResponse($response)
    {

        if($response['Message'] == 'Invalid pan!'){
            $response['success'] = $this->isSuccessfull($response['ResponseCode']);
            $response['Message'] = "Transaction Declined!";

        }elseif(strpos($response['Message'] , 'DECLINED') !== false || strpos($response['Message'] , 'decline') !== false || strpos($response['Message'] , 'Cancelled') !== false  ){

            $response['success'] = $this->isSuccessfull($response['ResponseCode']);
            $response['Message'] = "Transaction Declined!";
        }elseif(strpos($response['Message'], 'APPROVED') !== false){
            $response['success'] = $this->isSuccessfull($response['ResponseCode']);
        }else{
            $response['success'] = $this->isSuccessfull($response['ResponseCode']);
        }

        return $response;
    }

    /**
     * Update Invoice Status
     *
     * @param [type] $response
     * @return void
     */
    public function updateInvoiceStatus($response)
    {


        $data = ["ReceiptId",
                "ReferenceNum",
                "ResponseCode",
                "AuthCode",
                "TransTime",
                "TransDate",
                "Complete",
                "Message",
                "TransAmount",
                "CardType",
                "TransID",
                "TimedOut",
        ];
        $res = [];

        $responseData = $response->getMpgResponseData();
        foreach ($data as $element) {
            if(isset($responseData[$element])){
                $res[$element] = $responseData[$element];
            }
        }
        // update invoice status
        event(new InvoicePaid($this->invoice, $res , $this->paymentGateway));
    }
}
