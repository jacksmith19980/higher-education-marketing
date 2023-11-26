<?php

namespace App\Http\Controllers\Tenant\School;

use Log;
use Auth;
use Response;
use App\School;
use App\Payment\Payment;
use Illuminate\Http\Request;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\Student;
use App\Tenant\Models\Application;
use App\Http\Controllers\Controller;
use App\Tenant\Models\InvoiceStatus;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function show(School $school, Application $application, Invoice $invoice)
    {
        $payment = $application->PaymentGateways()->orderByDesc('id')->first();
        $field = $payment->field;
        $student = auth()->guard('student')->user();

        if ($invoice->canBePaid($payment)) {
            abort(403);
        }

        if ($student && ! $student->is($invoice->student)) {
            if ($invoice->student->parent && $invoice->student->parent->is($student)) {
                return $this->impersonateAndPay($invoice->student, $payment, $application, $invoice, $school);
            }

            return redirect()->back();
        }

        $sender = $student && $student->role == 'student' ? $student->parent : $student;

        return view('front.applications.payment', compact('payment', 'application', 'invoice', 'school', 'sender', 'student', 'field'));
    }

    protected function impersonateAndPay($student, $payment, $application, $invoice, $school)
    {
        if (! $student) {
            abort(403);
        }

        app(ChildImpersonatController::class)->impersonate($student);
        $sender = $student->parent;

        return view('front.applications.payment', compact('payment', 'application', 'invoice', 'school', 'sender', 'student'));
    }

    public function pay(School $school, Application $application, Invoice $invoice, Request $request)
    {
        $payment = new Payment($application->paymentGateway(), $request, $invoice);
        $result = $payment->pay();

        if ($result instanceof RedirectResponse){
            return $result->withErrors("INVALID PAYMENT");
        }

        $response = [
            'status' => $result['status'],
            'response' => $result['response'],
            'extra' => [
                'message' => $result['message'],
                'redirect' => $result['redirect'] ?? false,
            ],
        ];

        if ($result['status'] == 200) {
            $html = view('front.applications.payment-success-message')->render();
            $response['extra']['html'] = $html;
        }

        return response()->json($response);
    }

    public function track(School $school, Student $student = null, Request $request)
    {
        if ($request->has('gateway')) {
            $gateway = $request->gateway;

            return app('App\\Payment\\'.ucwords($gateway).'Payment')->response($request);
        }

        $invoice = Invoice::where('uid', $request->invoice_number)->first();
        $invoiceStatus = new InvoiceStatus();
        $invoiceStatus->status = 'Paid';
        $invoiceStatus->properties = $request->all();
        $invoiceStatus->invoice_id = $invoice->id;
        $invoiceStatus->save();
    }

    public function response(Request $request)
    {
        Log::debug(json_encode($request->all()));
    }
}
