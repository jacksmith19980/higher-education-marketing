<?php

namespace App\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Payment\Payment;
use App\Payment\PaymentInterface;
use App\Tenant\Models\Student;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizePayment extends Payment implements PaymentInterface
{
    public function processPayment($request)
    {
        $api_login_id = $this->paymentGateway->properties['api_login_id'];

        $transaction_key = $this->paymentGateway->properties['transaction_key'];

        $api_key = $this->paymentGateway->properties['api_key'];

        $isAjax = ($this->request->has('ajax')) ? true : false;

        $total = $this->invoice->total;

        if (isset($this->paymentGateway->properties['is_sandbox_account'])) {
            $env = ANetEnvironment::SANDBOX;
        } else {
            $env = ANetEnvironment::PRODUCTION;
        }

        $auth = new AnetAPI\MerchantAuthenticationType();

        $auth->setName($api_login_id);

        $auth->setTransactionKey($transaction_key);

        $refId = 'ref-'.time();

        // Set Payment

        $paymentOne = $this->payment($this->request);

        $customerAddress = $this->customerAddress($this->request->user());

        $customerData = $this->customerData($this->request->user());

        // Set Order

        $order = $this->order($this->invoice->uid);

        //Set Transaction

        $transaction = $this->transaction($total, $order, $paymentOne, $customerAddress, $customerData);

        // Set Request
        $request = $this->request($auth, $refId, $transaction);

        // Set Controller
        $controller = new AnetController\CreateTransactionController($request);

        if ($response = $controller->executeWithApiResponse($env)) {
            $isSuccessful = $response->getMessages()->getResultCode() == 'Ok';
            $message = $this->extractResponseMessage($isSuccessful, $response->getTransactionResponse());
        } else {
            $isSuccessful = false;
            $message = 'No response returned';
        }

        return $this->processResponse($isSuccessful, $isAjax, $message, $response);
    }

    /**
     * Update Invoice Status

     *

     * @param [type] $response

     * @return void
     */
    public function updateInvoiceStatus($response)
    {
        $res = $response->getTransactionResponse();
        $details = [
            'Transaction ID'    => $res->getTransId(),
            'Response Code'     => $res->getResponseCode(),
            'Account Type'      => $res->getAccountType(),
            'Account Number'    => $res->getAccountNumber(),
        ];
        // update invoice status
        event(new InvoicePaid($this->invoice, $res));
    }

    /**
     * Extract Response <essages

     *

     * @param bool $isSuccessful

     * @param [Obj] $tresponse

     * @return void
     */
    protected function extractResponseMessage($isSuccessful, $tresponse)
    {
        if ($isSuccessful) {
            if ($tresponse != null && $tresponse->getMessages() != null) {
                $message = ' Successfully created transaction with Transaction ID: '.$tresponse->getTransId().'<br />';

                $message .= ' Transaction Response Code: '.$tresponse->getResponseCode().'<br />';

                $message .= ' Description: '.$tresponse->getMessages()[0]->getDescription().'<br />';
            } else {
                echo 'Transaction Failed <br />';

                if ($tresponse->getErrors() != null) {
                    $message .= ' Error Code  : '.$tresponse->getErrors()[0]->getErrorCode().'<br />';

                    $message .= ' Error Message : '.$tresponse->getErrors()[0]->getErrorText().'<br />';
                }
            }
        } else {
            $message = "Transaction Failed \n";

            if ($tresponse != null && $tresponse->getErrors() != null) {
                $message .= ' Error Code  : '.$tresponse->getErrors()[0]->getErrorCode().'<br />';

                $message .= ' Error Message : '.$tresponse->getErrors()[0]->getErrorText().'<br />';
            }
        }

        if (! $message) {
            $message = 'Transaction Failed';
        }

        return $message;
    }

    /**
     * Set customer Address

     *

     * @param [Student] $student

     * @return AnetAPI\CustomerAddressType
     */
    protected function customerAddress(Student $student)
    {

        // Set the customer's Bill To address

        $customerAddress = new AnetAPI\CustomerAddressType();

        $customerAddress->setFirstName($student->first_name);

        $customerAddress->setLastName($student->last_name);

        return $customerAddress;
    }

    /**
     * Set Customer Identity

     *

     * @param [Student] $student

     * @return AnetAPI\CustomerDataType
     */
    protected function customerData(Student $student)
    {
        $customerData = new AnetAPI\CustomerDataType();

        $customerData->setType('individual');

        $customerData->setId($student->id);

        $customerData->setEmail($student->email);

        return $customerData;
    }

    /**
     * Set CreditCard Information

     *

     * @param [type] $request

     * @return AnetAPI\CreditCardType
     */
    protected function creditCard($request)
    {
        $creditCard = new AnetAPI\CreditCardType();

        $creditCard->setCardNumber(str_replace(' ', '', $request->cc_number));

        $creditCard->setExpirationDate($request->cc_expiry);

        $creditCard->setCardCode($request->cc_cvs);

        return $creditCard;
    }

    /**
     * Set Payment Information

     *

     * @param [type] $request

     * @return AnetAPI\PaymentType
     */
    protected function payment($request)
    {
        $creditCard = $this->creditCard($request);

        $paymentOne = new AnetAPI\PaymentType();

        $paymentOne->setCreditCard($creditCard);

        return $paymentOne;
    }

    /**
     * Create Order;

     *

     * @return AnetAPI\OrderType
     */
    protected function order($invoiceNumber)
    {

        // Create order information

        $order = new AnetAPI\OrderType();

        $order->setInvoiceNumber($invoiceNumber);

        $order->setDescription('Application Fees');

        return $order;
    }

    /**
     * Set Transaction

     *

     * @param [type] $total

     * @param [AnetAPI\OrderType] $order

     * @param [AnetAPI\PaymentType] $paymentOne

     * @param [AnetAPI\CustomerAddressType] $customerAddress

     * @param [AnetAPI\CustomerDataType] $customerData

     * @return AnetAPI\TransactionRequestType
     */
    protected function transaction($total, AnetAPI\OrderType $order, AnetAPI\PaymentType $paymentOne, AnetAPI\CustomerAddressType $customerAddress, AnetAPI\CustomerDataType $customerData)
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();

        $transactionRequestType->setTransactionType('authCaptureTransaction');

        $transactionRequestType->setAmount($total);

        $transactionRequestType->setOrder($order);

        $transactionRequestType->setPayment($paymentOne);

        $transactionRequestType->setBillTo($customerAddress);

        $transactionRequestType->setCustomer($customerData);

        return $transactionRequestType;
    }

    /**
     * Set Request

     *

     * @param [AnetAPI\MerchantAuthenticationType] $auth

     * @param [type] $refId

     * @param [AnetAPI\TransactionRequestType] $transaction

     * @return AnetAPI\CreateTransactionRequest
     */
    protected function request(AnetAPI\MerchantAuthenticationType $auth, $refId, AnetAPI\TransactionRequestType $transaction)
    {

        // Assemble the complete transaction request

        $request = new AnetAPI\CreateTransactionRequest();

        $request->setMerchantAuthentication($auth);

        $request->setRefId($refId);

        $request->setTransactionRequest($transaction);

        return $request;
    }
}
