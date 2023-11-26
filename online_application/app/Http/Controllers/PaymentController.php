<?php

namespace App\Http\Controllers;

use App\Helpers\School\ModelHelpers;
use App\Services\Payment\StripeService;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoicePayments;
use App\Tenant\Models\Student;
use Illuminate\Http\Request;
use Response;

class PaymentController extends Controller
{
    public function paymentStripe()
    {
        return view('back.payment.stripe');
    }

    public function paymentProcess(Request $request)
    {
        $payment_service = new StripeService('stripe_secret_key');
        $request->validate([
            'card_no'       => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear'  => 'required',
            'cvvNumber'     => 'required',
        ]);

        $payment_service->paymentProcess();
    }

    public function create(Request $request)
    {
        $student = null;
        if (isset($request->student) && $request->student != '') {
            $student = Student::findOrfail($request->student);
        }

        $invoice = null;
        if (isset($request->invoice) && $request->invoice != '') {
            $invoice = Invoice::findOrfail($request->invoice);
        }

        $students = ModelHelpers::convertFirstNameLastnameInNameAssocWithId(
            Student::all()
        );

        return view('back.payment.payment-create', compact('students', 'student', 'invoice'));
    }

    public function edit(InvoicePayments $invoice_payment, Request $request)
    {
        $students = ModelHelpers::convertFirstNameLastnameInNameAssocWithId(
            Student::all()
        );

        $student = Student::findOrfail($request->student);

        return view(
            'back.payment.payment-create',
            compact('students', 'invoice_payment', 'student')
        );
    }

    public function destroyPoymorph($invoice_payment)
    {
        $payment = InvoicePayments::where('id', $invoice_payment)->firstOrFail();
        if ($response = $payment->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $payment->id],
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

    public function addProduct($payload)
    {
        $html =  view('back.applications.payments._partials.product' , [
                'product'   => null,
                'order'     => $payload['order']
            ]
        )->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html]
            ]
        );
    }
}
