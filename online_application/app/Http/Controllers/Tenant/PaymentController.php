<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Application\FieldsHelper;
use App\Helpers\Application\PaymentHelpers;
use App\Helpers\cart\CartHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Field;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoicePayments;
use App\Tenant\Models\PaymentGateway;
use App\Tenant\Models\Section;
use App\Tenant\Models\Setting;
use Illuminate\Http\Request;
use PDF;
use Response;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $application = Application::where('slug', $request->application)->with('sections')->first();
        $sections = $application->sections;
        $gateway = $request->gateway;
        $route = route('payments.store');

        return view(
            'back.applications.payments.'.$gateway.'.form',
            compact('route', 'gateway', 'application', 'sections')
        );
    }

    public function store(Request $request)
    {
        $section = Section::find($request->section);

        //$application = $section->applications()->first();
        $application = Application::find($request->application);

        $paymentGateWay = new PaymentGateway();
        $paymentGateWay->name = 'Payment-'.ucwords($request->gateway);
        $paymentGateWay->slug = $request->gateway;

        // Save Payment Properties
        $paymentGateWay->properties = $request->properties;

        $paymentGateWay->section()->associate($section);
        $paymentGateWay->application()->associate($application);

        if ($paymentGateWay->save()) {
            //  If Payment added to Application/Section
            if ($section) {
                // Create filed for the Payment Form
                $field = new Field();
                $field->label = $paymentGateWay->name;
                $field->published = true;
                $field->name = FieldsHelper::getFieldName($field->label);

                $field->field_type = 'payment';

                $properties = [
                        'smart'   => false,
                        'gateway' => $paymentGateWay->id,
                        'type'    => $request->gateway,
                    ];

                if ($field = app(FieldController::class)->saveFieldData($properties, $field, $section, null)) {
                    $paymentGateWay->field()->associate($field);
                    $paymentGateWay->save();

                    $html = view('back.applications._partials.field', compact('paymentGateWay', 'section', 'application', 'field'))->render();

                    return Response::json([
                            'status'    => 200,
                            'response'  => 'success',
                            'extra'     => ['html' => $html, 'section_id' => $section->id],
                    ]);
                }
            } else {
                // If Payment will be after the application is finished
                $html = view(
                    'back.applications._partials.payments',
                    compact('paymentGateWay', 'application')
                )->render();

                return Response::json([
                  'status' => 200,
                  'response' => 'success',
                  'extra' => ['html' => $html],
                ]);
            }
        }
    }

    public function edit(PaymentGateway $payment, Request $request)
    {

        $application = Application::where('slug', $request->application)->with('sections')->first();
        $sections = $application->sections;
        $gateway = $payment->slug;
        $route = route('payments.update', $payment);
        $method = 'PUT';
        return view(
            'back.applications.payments.'.$gateway.'.form',
            compact('route', 'gateway', 'application', 'payment', 'sections')
        );
    }

    public function update(PaymentGateway $payment, Request $request)
    {
        $payment->properties = $request->properties;
        if ($payment->save()) {
            $application = Application::find($request->appliction_id);

            $paymentGateWay = $payment;

            $html = view('back.applications._partials.payments', compact('paymentGateWay', 'application'))->render();

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra' => ['html' => $html, 'paymentID' => $payment->id],
                ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => 'fail',
                'extra' => ['message' => 'Something went wrong!'],
            ]);
        }
    }

    /**
     * Delete Payment
     * @param  [Integration] $Payment [description]
     * @return [JSON]                   [return rmeoved Payment Id to hide]
     */
    public function destroy(PaymentGateway $payment)
    {
        if ($response = optional($payment)->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $payment->id],
             ]);
        } else {
            return Response::json([
               'status'    => 404,
               'response'  => $response,
             ]);
        }
    }

    public function pay($school, Invoice $invoice, Request $request)
    {
        $paymentGateWay = $invoice->application->paymentGateway();
        $field = $paymentGateWay->field;
        $slug = $paymentGateWay->slug;
        $user = $invoice->student;
        $parent = $user->parent;
        $invoice->load('application');

        return view('front.payment.'.$slug.'.index', compact('school', 'invoice', 'paymentGateWay', 'user', 'parent', 'field'));
    }

    public function paymentType(Request $request, School $school, Application $application)
    {
        switch ($request->value) {
            case 'full-amount':
                $html = $this->fullAmount($application);
                break;
            case 'fixed-amount':
                $html = $this->fixedAmount($application);
                break;
            case 'variable-amount':
                $html = $this->variableAmount($application);
                break;
        }

        return Response::json([
              'status'    => 200,
              'response'  => 'success',
              'extra'     => ['html'  => $html],
          ]);
    }

    private function fullAmount($application)
    {
        $date = PaymentHelpers::getPaymentDate($application->properties['full_amount'], $application);
        $total = $total_price = CartHelpers::getCartTotalPrice(CartHelpers::getCart($application))['total'];

        return view(
            'front.applications.application-layouts.shared.partials.full-amount',
            compact('application', 'date', 'total')
        )->render();
    }

    private function fixedAmount($application)
    {
        $payment_type = $application->properties['fixed_amount'];
        $first_payment = PaymentHelpers::firstPayment($payment_type['first_payment'], $application);
        $first_payment_date = PaymentHelpers::getPaymentDate($application->properties['fixed_amount'], $application);

        $installments = PaymentHelpers::getFixedInstallments(
            $first_payment_date,
            $first_payment,
            $payment_type,
            $application
        );

        return view(
            'front.applications.application-layouts.shared.partials.installment',
            [
                'payment_type' => 'Installments',
                'first_payment' => $first_payment,
                'first_payment_date' => $first_payment_date,
                'installments' => $installments,
            ]
        )->render();
    }

    private function variableAmount($application)
    {
        $payment_type = $application->properties['variable_amount'];
        $first_payment = PaymentHelpers::firstPayment($payment_type['first_payment'], $application);
        $first_payment_date = PaymentHelpers::getPaymentDate($application->properties['variable_amount'], $application);

        $installments = PaymentHelpers::getVariableInstallments($first_payment, $payment_type, $application);

        return view(
            'front.applications.application-layouts.shared.partials.installment',
            [
                'payment_type' => 'Installments',
                'first_payment' => $first_payment,
                'first_payment_date' => $first_payment_date,
                'installments' => $installments,
            ]
        )->render();
    }

    public function storePolymorph($payload)
    {
        $invoices = $payload['payment']['invoices'];
        $student_id = $payload['payment']['student_id'];
        $payment_date = $payload['payment']['payment_date'];
        $payment_method = $payload['payment']['payment_method'];
        $reference_no = $payload['payment']['reference_no'];
        $payment_message = $payload['payment']['payment_message'];

        $is_update = isset($payload['payment']['payment_id']) &&
            $payload['payment']['payment_id'] != '' &&
            $payload['payment']['payment_id'] != null;

        if ($is_update) {
            $payment = InvoicePayments::where('id', $payload['payment']['payment_id'])->first();
            if ($invoices[0]['payment'] != null && $invoices[0]['payment'] != '') {
                $payment->uid = rand(100000, 1000000);
                $payment->amount_paid = $invoices[0]['payment'];
                $payment->status = '-';
                $payment->invoice_id = $invoices[0]['invoice_id'];
                $payment->student_id = $student_id;
                $payment->properties = [
                    'payment_message' => $payment_message,
                ];
                $payment->save();
            }
        } else {
            foreach ($invoices as $invoice) {
                if ($invoice['payment'] != null && $invoice['payment'] != '') {
                    $payment = new InvoicePayments();
                    $payment->uid = rand(100000, 1000000);
                    $payment->amount_paid = $invoice['payment'];
                    $payment->status = '-';
                    $payment->invoice_id = $invoice['invoice_id'];
                    $payment->student_id = $student_id;
                    $payment->properties = [
                        'payment_message' => $payment_message,
                    ];
                    $payment->save();
                }
            }
        }

        return response()->json([
            'status' => 200,
            'response' => 'success',
        ]);
    }

    public function pdf(InvoicePayments $payment, $action, Request $request)
    {
        $invoice = $payment->invoice;
        $invoice->load('booking', 'student', 'status', 'application');
        $settings = Setting::byGroup();
        $currency = isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '$';

        if ($action == 'view') {
            $html = view(
                'back.students.payments.payment',
                compact('invoice', 'settings', 'payment', 'currency')
            )->render();

            return PDF::loadHTML($html)->stream();
        }

        if ($action == 'download') {
            $name = '.pdf';

            return PDF::loadView('back.students.payments.payment',
                compact('invoice', 'settings', 'payment', 'currency'))
                ->download($name);
        }
    }
}
