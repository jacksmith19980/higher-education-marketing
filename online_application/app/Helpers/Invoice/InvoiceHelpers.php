<?php

namespace App\Helpers\Invoice;

use App\Helpers\Application\ApplicationHelpers;
use App\Helpers\Application\PaymentHelpers;
use App\Helpers\Application\ProgramHelpers;
use App\Helpers\cart\CartHelpers;
use App\Helpers\School\CourseHelpers;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoiceStatus;
use App\Tenant\Models\Setting;

class InvoiceHelpers
{
    public static function createFullAmountInvoice($application, $student)
    {
        $total = $total_price = CartHelpers::getCartTotalPrice(CartHelpers::getCart($application))['total'];
        $due_date = PaymentHelpers::getPaymentDate($application->properties['full_amount'], $application);

        self::addInvoice($application, $student, $total, $due_date);
    }

    public static function createFixedAmountInvoices($application, $student)
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

        self::addInvoice($application, $student, $first_payment, $first_payment_date);

        foreach ($installments as $installment) {
            self::addInvoice($application, $student, $installment['amount'], $installment['date']);
        }
    }

    public static function createVariableAmountInvoices($application, $student)
    {
        $payment_type = $application->properties['variable_amount'];
        $first_payment = PaymentHelpers::firstPayment($payment_type['first_payment'], $application);
        $first_payment_date = PaymentHelpers::getPaymentDate($application->properties['variable_amount'], $application);

        $installments = PaymentHelpers::getVariableInstallments($first_payment, $payment_type, $application);

        self::addInvoice($application, $student, $first_payment, $first_payment_date);

        foreach ($installments as $installment) {
            self::addInvoice($application, $student, $installment['amount'], $installment['date']);
        }
    }

    protected static function addInvoice($application, $student, $total, $due_date)
    {
        $paymentGateWay = $application->PaymentGateway();

        $invoice = new Invoice();
        $invoice->uid = rand(100000, 1000000);
        $invoice->total = $total;
        $invoice->payment_gateway = $paymentGateWay ? $paymentGateWay->slug : null;
        $invoice->application_id = $application->id;
        $invoice->student_id = $student->id;
        $invoice->enabled = true;
        $invoice->due_date = $due_date;
        $invoice->save();

        $status = new InvoiceStatus();
        $status->status = 'Invoice Created';
        $invoice->status()->save($status);
    }

    public static function addSubmissionInvoice($application, $submission, $student, $total, $due_date)
    {
        $paymentGateWay = $application->PaymentGateway();

        $invoice = new Invoice();
        $invoice->uid = rand(100000, 1000000);
        $invoice->total = $total;
        $invoice->payment_gateway = $paymentGateWay ? $paymentGateWay->slug : null;
        $invoice->submission_id = $submission->id;
        $invoice->application_id = $application->id;
        $invoice->student_id = $student->id;
        $invoice->enabled = true;
        $invoice->due_date = $due_date;
        $invoice->save();

        $status = new InvoiceStatus();
        $status->status = 'Invoice Created';
        $invoice->status()->save($status);

        return $invoice;
    }

    public static function addProgramInvoice($program, $student_id, $total, $paymentGateWay)
    {
        $invoice = new Invoice();
        $invoice->uid = rand(100000, 1000000);
        $invoice->total = $total;
        $invoice->payment_gateway = $paymentGateWay;
        $invoice->student_id = $student_id;
        $invoice->enabled = true;
        $invoice->program_id = $program;
        $invoice->save();

        $status = new InvoiceStatus();
        $status->status = 'Invoice Created';
        $invoice->status()->save($status);

        return $invoice;
    }

    public static function getProductsBycategory($category)
    {
        $products = null;
        switch (strtolower(trim($category))) {
            case 'application':
                $products = ApplicationHelpers::getApplication();
                break;
            case 'course':
                $products = CourseHelpers::getCoursesInArrayOnlyTitleId();
                break;
            case 'program':
                $products = ProgramHelpers::getProgramInArrayOnlyTitleId();
                break;
            case 'addon':
                $products = ApplicationHelpers::getAddons();
                break;
        }

        return $products;
    }

    public static function defaultCurrency($amount)
    {
        $settings = Setting::byGroup();
        $default_currency = isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD';

        return $amount.' '.$default_currency;
    }

    public static function getStudentBalance($student)
    {
//        $student = Student::findOrFail($student_id);

        $student_balance = $student->invoices->map(function ($invoice, $key) {
            return self::invoiceBalance($invoice);
        })->reduce(function ($carry, $balance) {
            return $carry + $balance;
        });

        return $student_balance;
    }

    public static function invoiceBalance(Invoice $invoice)
    {
//        $invoice = Invoice::findOrfail($invoice_id);

        $payments_amount = $invoice->payments->reduce(function ($carry, $payment) {
            return $carry + $payment->amount_paid;
        });

        return $invoice->total - $payments_amount;
    }

    public static function addPolymorphInvoice($invoice_payload)
    {
        $invoice = new Invoice();
        $invoice->uid = self::newInvoiceNumber();

        $invoice->payment_gateway = isset($invoice_payload['payment_gateway']) ? $invoice_payload['payment_gateway'] : '-';
        $invoice->student_id = isset($invoice_payload['student_id']) ? $invoice_payload['student_id'] : 0;
        $invoice->due_date = isset($invoice_payload['due_date']) ? $invoice_payload['due_date'] : now();
        $invoice->created_at = isset($invoice_payload['invoice_date']) ? $invoice_payload['invoice_date'] : now();
        $invoice->enabled = true;
        $invoice->total = $invoice_payload['total'];
        $invoice->properties = [
            'invoice_message' => isset($invoice_payload['invoice_message']) ? $invoice_payload['invoice_message'] : '',
            'billing_address' => isset($invoice_payload['billing_address']) ? $invoice_payload['billing_address'] : '',
        ];
        $invoice->save();

        return $invoice;
    }

    public static function addInvoiceable($invoice, $product, $student_id, $payload)
    {
        $invoiceable_payload = [
            'uid' => self::newInvoiceableNumber(),
            'student_id' => $student_id,
            'quantity' => $payload['quantity'],
            'amount' => $payload['amount'],
            'properties' => json_encode([
                    'description' => $payload['description'],
            ]),
        ];

        $invoiceable_payload['title'] = isset($product->title) ? $product->title : $payload['title'];
        $product->invoiceable()->save($invoice, $invoiceable_payload);
    }

    public static function newInvoiceNumber()
    {
        return rand(100000, 1000000);
    }

    public static function newInvoiceableNumber()
    {
        return rand(100000, 1000000);
    }
}
