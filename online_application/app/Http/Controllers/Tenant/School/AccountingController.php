<?php

namespace App\Http\Controllers\Tenant\School;

use App\Helpers\Invoice\InvoiceHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoicePayments;
use App\Tenant\Models\Setting;
use Illuminate\Http\Request;
use Response;

class AccountingController extends Controller
{
    public function index()
    {
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

        return view('front.finance.index', compact('datatablei18n'));
    }

    public function getInvoicePayment(Request $request)
    {
        //# Read value
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = $request->columns[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

        //# Custom Field value
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $student_id = $request->student_id;

        $data = [];

        //# Total number of records without filtering
        $totalRecordsInvoices = Invoice::select('count(*) as allcount')
            ->leftjoin('students', 'invoices.student_id', '=', 'students.id')
            ->count();
        $totalRecordsInvoicesWithFilter = Invoice::select('count(*) as allcount')
            ->leftjoin('students', 'invoices.student_id', '=', 'students.id');

        $totalRecordsPayments = InvoicePayments::select('count(*) as allcount')->count();
        $totalRecordsPaymentsWithFilter = InvoicePayments::select('count(*) as allcount');

        $invoices = Invoice::with('student', 'payments')->select('*');
        $payments = InvoicePayments::select('*');

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

//            $invoices_by_student = Invoice::where('student_id', $student_id)->select('id')->get();
//
//            $invoices_by_student_ids = array_map(function ($val) {
//                return $val['id'];
//            }, $invoices_by_student->toArray());

//            $searchPaymentSpecificStudent= function ($query) use ($invoices_by_student_ids) {
//                $query->whereIn('invoice_payments.invoice_id', $invoices_by_student_ids);
//            };
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
                'date'     => isset($payment->created_at) ? $payment->created_at->format('Y-m-d') : '',
                'type'     => 'Payment',
                'no'       => '',
                'student'  => isset($payment->invoice->student) ? htmlentities(ucwords(mb_strtolower($payment->invoice->student->name))) : '',
                'balance'  => InvoiceHelpers::defaultCurrency(number_format(0, 2)),
                'total'    => InvoiceHelpers::defaultCurrency(number_format($payment->amount_paid, 2)),
                'status'   => isset($payment->status) ? ucfirst($payment->status) : '',
                'id'       => $payment->id,
                'uid'       => $payment->uid,
                'student_id'  => isset($payment->invoice->student) ? $payment->invoice->student->id : '',
            ];
        }

        $school = School::byUuid(session('tenant'))->firstOrFail();

        foreach ($invoices as $invoice) {
            $total = $invoice->total;
            $invoice_payments = $invoice->payments;
            $paid = 0;

            foreach ($invoice_payments as $payment) {
                $paid = $paid + $payment->amount_paid;
            }

            $balance = $total - $paid;
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
                'type'          => 'Invoice',
                'no'            => $invoice->uid,
                'student'       => isset($invoice->student) ? htmlentities(ucwords(mb_strtolower($invoice->student->name))) : '',
                'balance'       => InvoiceHelpers::defaultCurrency(number_format($balance, 2)),
                'total'         => InvoiceHelpers::defaultCurrency(number_format($total, 2)),
                'status'        => '',
                'id'            => $invoice->id,
                'uid'           => $invoice->uid,
                'student_id'    => isset($invoice->student) ? $invoice->student->id : '-1',
                'view'          => route('invoice.pdf.action', ['invoice' => $invoice, 'action' => 'view']),
                'download'      => route('invoice.pdf.action', ['invoice' => $invoice, 'action' => 'download']),
                'paymentLink'   => $paymentLink,
            ];
        }

        //$data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        //dd($data);
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
