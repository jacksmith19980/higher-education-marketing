<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Invoice\InvoiceHelpers;
use App\Helpers\Permission\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoicePayments;
use App\Tenant\Models\Setting;
use Illuminate\Http\Request;
use Response;

class AccountingController extends Controller
{
    const  PERMISSION_BASE = "application";


    public function index()
    {
        if (! PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return redirect(route(PermissionHelpers::REDIRECTIO_ON_FAIL));
        }

        if (config('app.locale') == 'en') {
            $datatablei18n = 'English';
        }

        switch (config('app.locale')) {
            case 'en':
                $datatablei18n = 'English';
                break;
            case 'es':
                $datatablei18n = 'Spanish';
                break;
            case 'fr':
                $datatablei18n = 'French';
                break;
            default:
                $datatablei18n = 'English';
        }

        return view('back.accounting.index', compact('datatablei18n'));
    }

    public function getInvoicePayment(Request $request)
    {
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $request->columns[$columnIndex]['data'];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        //# Custom Field value
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $student_id = $request->student_id;

        $data = [];

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            $response = [
                'draw' => intval($draw),
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => $data,
            ];

            return Response::json($response);
        }

        //# Total number of records without filtering
        $totalRecordsInvoices = Invoice::select('count(*) as allcount')
            ->leftjoin('students', 'invoices.student_id', '=', 'students.id')
            ->count();
        $totalRecordsInvoicesWithFilter = Invoice::select('count(*) as allcount')
            ->leftjoin('students', 'invoices.student_id', '=', 'students.id');

        $totalRecordsPayments = InvoicePayments::select('count(*) as allcount')->count();
        $totalRecordsPaymentsWithFilter = InvoicePayments::select('count(*) as allcount');

        $invoices = Invoice::with('student', 'payments')->select('*');
        $payments = InvoicePayments::with('invoice', 'invoice.student')->select('*');

        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $searchInvoiceDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('invoices.created_at', [$start_date, $end_date]);
            };
            $searchPaymentDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('invoice_payments.created_at', [$start_date, $end_date]);
            };

            $totalRecordsInvoicesWithFilter->where($searchInvoiceDatesRange);
            $totalRecordsPaymentsWithFilter->where($searchPaymentDatesRange);

            $invoices->where($searchInvoiceDatesRange);
            $payments->where($searchPaymentDatesRange);
        }

        if (isset($student_id) && $student_id != '') {
            $searchInvoiceSpecificStudent = function ($query) use ($student_id) {
                $query->where('invoices.student_id', $student_id);
            };

            $searchPaymentSpecificStudent = function ($query) use ($student_id) {
                $query->where('invoice_payments.student_id', $student_id);
            };

            $totalRecordsInvoicesWithFilter->where($searchInvoiceSpecificStudent);
            $totalRecordsPaymentsWithFilter->where($searchPaymentSpecificStudent);

            $invoices->where($searchInvoiceSpecificStudent);
            $payments->where($searchPaymentSpecificStudent);
        }

        $totalRecords = $totalRecordsInvoices + $totalRecordsPayments;

        $invoices = $invoices->get();
        $payments = $payments->get();

        $totalRecordsInvoicesWithFilter = $totalRecordsInvoicesWithFilter->count();
        $totalRecordsPaymentsWithFilter = $totalRecordsPaymentsWithFilter->count();

        $totalFilteredRecords = $totalRecordsInvoicesWithFilter + $totalRecordsPaymentsWithFilter;

        foreach ($payments as $payment) {
            $data[] = [
                'date'          => isset($payment->created_at) ? $payment->created_at->format('Y-m-d') : '',
                'dueDate'       => '',
                'type'          => 'Payment',
                'no'            => '',
                'student'       => isset($payment->invoice->student) ? htmlentities(ucwords(mb_strtolower($payment->invoice->student->name))) : '',
                'balance'       => InvoiceHelpers::defaultCurrency(number_format(0, 2)),
                'total'         => InvoiceHelpers::defaultCurrency(number_format($payment->amount_paid, 2)),
                'status'        => isset($payment->status) ? ucfirst($payment->status) : '',
                'paymentDetail' => [],
                'paymentMethod' => '',
                'id'            => $payment->id,
                'uid'           => $payment->uid,
                'student_id'    => isset($payment->invoice->student) ? $payment->invoice->student->id : '',
                'view'          => route('payment.pdf.action', ['payment' => $payment, 'action' => 'view']),
                'download'      => route('payment.pdf.action', ['payment' => $payment, 'action' => 'download']),
            ];
        }

        $settings = Setting::byGroup();
        $default_currency = isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD';
        $school = School::byUuid(session('tenant'))->firstOrFail();

        foreach ($invoices as $invoice) {
            $total = $invoice->total;
            $invoice_payments = $invoice->payments;
            $paid = 0;

            foreach ($invoice_payments as $payment) {
                $paid = $paid + $payment->amount_paid;
            }
            $balance = $total - $paid;
            $invoiceable =  $invoice->invoiceables()->orderBy('id', 'DESC')->first();
            if( is_object($invoiceable) && is_object($invoice)){

                $paymentDetail = [];
                $paymentMethod = [];

                foreach ($invoice->status as $paymentStatus) {
                    // Stripe
                    if (isset($paymentStatus->properties['Card Brand'])) {
                        $paymentMethod[] = 'Stripe: ' . ucfirst($paymentStatus->properties['Card Brand']) . ' ****' . $paymentStatus->properties['Card Last Four'];
                        $paymentDetail[] = [
                            ['name' => 'Method', 'value' => 'Stripe'],
                            ['name' => 'Amount', 'value' => $paymentStatus->properties['Amount']],
                            ['name' => 'Card', 'value' => ucfirst($paymentStatus->properties['Card Brand']) . ' ****' . $paymentStatus->properties['Card Last Four']],
                        ];
                    }

                    // Paypal
                    if (isset($paymentStatus->properties['Invoice Number'])) {
                        $paymentMethod[] = 'Paypal: ' .' Order ID = ' . $paymentStatus->properties['Order ID'];
                        $paymentDetail[] = [
                            ['name' => 'Method', 'value' => 'Paypal'],
                            ['name' => 'Amount', 'value' => $paymentStatus->properties['Amount']],
                            ['name' => 'Order ID', 'value' => $paymentStatus->properties['Order ID']],
                        ];
                    }

                    $monirisCardTypes = [
                        'V' => 'Visa',
                        'M' => 'MasterCard'
                    ];

                    // Moneris
                    if (isset($paymentStatus->properties['ReceiptId'])) {
                        $paymentMethod[] = 'Moneris: ' .' ReferenceNum = ' . $paymentStatus->properties['ReferenceNum'] . '(' . (isset($monirisCardTypes[$paymentStatus->properties['CardType']]) ? $monirisCardTypes[$paymentStatus->properties['CardType']] : $paymentStatus->properties['CardType']) . ')';
                        $paymentDetail[] = [
                            ['name' => 'Method', 'value' => 'Moneris'],
                            ['name' => 'ReferenceNum', 'value' => $paymentStatus->properties['ReferenceNum']],
                            ['name' => 'Amount: ', 'value' => $paymentStatus->properties['TransAmount']],
                            ['name' => 'Card: ', 'value' => isset($monirisCardTypes[$paymentStatus->properties['CardType']]) ? $monirisCardTypes[$paymentStatus->properties['CardType']] : $paymentStatus->properties['CardType']],
                            ['name' => 'Date: ', 'value' => $paymentStatus->properties['TransDate'] . ' ' . $paymentStatus->properties['TransTime']],
                        ];
                    }

                    // Creditagricole
                    if (isset($paymentStatus->properties['Device Number'])) {
                        $paymentMethod[] = 'Creditagricole: ' .' Transaction Number = ' . $paymentStatus->properties['Transaction Number'] . ', Date = ' . $paymentStatus->properties['Date of the transaction'] . ' ' . $paymentStatus->properties['Time of the transaction'];
                        $paymentDetail[] = [
                            ['name' => 'Method', 'value' => 'Creditagricole'],
                            ['name' => 'Amount', 'value' => $paymentStatus->properties['Amount']],
                            ['name' => 'Transaction Number', 'value' => $paymentStatus->properties['Transaction Number']],
                            ['name' => 'Date', 'value' => $paymentStatus->properties['Date of the transaction'] . ' ' . $paymentStatus->properties['Time of the transaction']],
                        ];
                    }

                    // Bambora
                    if (isset($paymentStatus->properties['order_number'])) {
                        $paymentMethod[] = 'Bambora: ' .' Payment Method = ' . $paymentStatus->properties['payment_method'];
                        $paymentDetail[] = [
                            ['name' => 'Method', 'value' => 'Bambora'],
                            ['name' => 'Amount', 'value' => $paymentStatus->properties['amount']],
                            ['name' => 'Payment Method', 'value' => $paymentStatus->properties['payment_method'] . ' ****' . $paymentStatus->properties['Card Last Four']],
                            ['name' => 'Method', 'value' => $paymentStatus->properties['order_number']],
                            ['name' => 'Type', 'value' => $paymentStatus->properties['type']]
                        ];
                    }

                    // Flywire
                    if (isset($paymentStatus->properties['Transaction Id']) and isset($paymentStatus->properties['At'])) {
                        $paymentMethod[] = 'Flywire: ' .' Transaction Id = ' . $paymentStatus->properties['Transaction Id'];
                        $paymentDetail[] = [
                            ['name' => 'Method: ', 'value' => 'Flywire'],
                            ['name' => 'Amount', 'value' => $paymentStatus->properties['Amount']],
                            ['name' => 'Transaction Id', 'value' => $paymentStatus->properties['Transaction Id']],
                            ['name' => 'Date', 'value' => $paymentStatus->properties['At']],
                        ];
                    }

                    // Datatrans
                    if (isset($paymentStatus->properties['ACQ Authorization Code'])) {
                        $paymentMethod[] = 'Datatrans: ' .' Transaction Id = ' . $paymentStatus->properties['Transaction Id'];
                        $paymentDetail[] = [
                            ['name' => 'Method: ', 'value' => 'Datatrans'],
                            ['name' => 'Amount', 'value' => $paymentStatus->properties['Amount'] . ' ' . $paymentStatus->properties['Currency']],
                            ['name' => 'Payment Method', 'value' => $paymentStatus->properties['Payment Method']],
                            ['name' => 'Transaction Id', 'value' => $paymentStatus->properties['Transaction Id']],
                            ['name' => 'Ref No', 'value' => $paymentStatus->properties['Ref No']],
                        ];
                    }
                }

                $paymentLink = null;
                if (!$invoice->isPaid && $invoice->application) {
                    $paymentLink = route('application.payment.show.no-login', [
                        'school'=> $school,
                        'application' => $invoice->application,
                        'invoice' => $invoice,
                    ]);
                }

                $data[] = [
                    'date'          => isset($invoice->created_at) ? $invoice->created_at->format('Y-m-d') : '',
                    'dueDate'       => $invoice->due_date,
                    'type'          => 'Invoice',
                    'no'            => $invoice->uid,
                    'student'       => isset($invoice->student) ? htmlentities(ucwords(mb_strtolower($invoice->student->name))) : '',
                    'balance'       => number_format($balance, 2).' '.$default_currency,
                    'total'         => number_format($total, 2).' '.$default_currency,
                    'status'        => (is_object($invoice->lastStatus())) ? $invoice->lastStatus()->status : '',
                    'paymentDetail' => $paymentDetail,
                    'paymentMethod' => implode(" - ", $paymentMethod),
                    'id'            => $invoice->id,
                    'uid'           => $invoice->uid,
                    'student_id'    => isset($invoice->student) ? $invoice->student->id : '-1',
                    'view'          => route('invoice.pdf.action', ['invoice' => $invoice, 'action' => 'view']),
                    'download'      => route('invoice.pdf.action', ['invoice' => $invoice, 'action' => 'download']),
                    'paymentLink'   => $paymentLink,
                ];
            }
        }

        if ($searchValue != '') {
            $data = array_filter($data, function ($v, $k) use ($searchValue) {
                $searchColumns = ['date', 'type', 'no', 'student', 'balance', 'total', 'status'];
                foreach ($searchColumns as $searchColumn) {
                    if (stripos($v[$searchColumn], $searchValue) !== false) {
                        return true;
                    }
                }

                return false;
            }, ARRAY_FILTER_USE_BOTH);
            $totalFilteredRecords = count($data);
        }

        usort($data, function ($a, $b) use ($columnName, $columnSortOrder) {
            $order = $columnSortOrder == 'asc' ? 1 : -1;
            switch ($columnName) {
                case 'no':
                case 'balance':
                case 'total':
                    if ($a[$columnName] == '') {
                        $a[$columnName] = 0;
                    }
                    if ($b[$columnName] == '') {
                        $b[$columnName] = 0;
                    }

                    return ((float) $a[$columnName] - (float) $b[$columnName]) * $order;
                    break;
                case 'date':
                    $t1 = strtotime($a[$columnName]);
                    $t2 = strtotime($b[$columnName]);

                    return ($t1 - $t2) * $order;
                    break;
                case 'date':
                case 'type':
                case 'student':
                case 'status':
                    return strcmp($a[$columnName], $b[$columnName]) * $order;
                    break;
            }
        });

        $data = array_slice($data, $row, $rowperpage);

        //# Response
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalFilteredRecords,
            'aaData' => $data,
        ];

        return Response::json($response);
    }
}
