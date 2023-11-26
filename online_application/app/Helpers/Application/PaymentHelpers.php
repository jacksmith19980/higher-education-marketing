<?php

namespace App\Helpers\Application;

use App\Helpers\cart\CartHelpers;
use App\Helpers\School\PluginsHelper;
use App\Tenant\Models\Application;
use App\Tenant\Models\InvoicePayments;
use App\Tenant\Models\Plugin;
use Carbon\Carbon;

/**
 * Application Helper
 */
class PaymentHelpers
{
    public static function getMonthsList()
    {
        return[

            '' => '--Month--',

            '01' => 'January',

            '02' => 'February',

            '03' => 'March',

            '04' => 'April',

            '05' => 'May',

            '06' => 'June',

            '07' => 'July',

            '08' => 'August',

            '09' => 'September',

            '10' => 'October',

            '11' => 'November',

            '12' => 'December',

        ];
    }

    public static function getYearsList()
    {
        $currentYear = (int) date('y');

        $years = [];

        $years[''] = '--Year--';

        for ($i = $currentYear; $i < $currentYear + 10; $i++) {
            $years[$i] = '20'.$i;
        }

        return $years;
    }

    public static function getInvoiceStatusProperties($properties)
    {
        return [

            'ID'         => $properties['id'],

            'Reference'  => $properties['reference'],

            'Date'       => $properties['date'],

        ];
    }

    public static function getCAProperties($properties, $user = null, $invoice = null)
    {
        $properties['dateTime'] = date('c');
        $properties['pbx_cmd'] = $invoice->uid;

        if (! $user) {
            $user = $invoice->student;
        }

        $properties['pbx_porteur'] = $user->email;

        // Get Current URL
        $currentUrl = url()->current();

        $properties['pbx_retour'] = 'amount:M;ref:R;auto:A;appel:T;abo:B;reponse:E;transaction_number:S;pays:Y;signature:K;ip:I;created_at_time:Q;card:Z;created_at_date:W;';

        $properties['pbx_effectue'] = $currentUrl.'?effectue';
        $properties['pbx_annule'] = $currentUrl.'?annule';
        $properties['pbx_refuse'] = $currentUrl.'?refuse';

        // Get Total Payment
        $pbx_total = str_replace(',', '', $invoice->total);
        $pbx_total = str_replace('.', '', $invoice->total);
        //$properties['pbx_total'] =  $pbx_total * 100;
        $properties['pbx_total'] = $pbx_total;

        $msg = 'PBX_SITE='.$properties['pbx_site'].
        '&PBX_RANG='.$properties['pbx_rang'].
        '&PBX_IDENTIFIANT='.$properties['pbx_identifiant'].
        '&PBX_TOTAL='.$properties['pbx_total'].
        '&PBX_DEVISE=978'.
        '&PBX_CMD='.$properties['pbx_cmd'].
        '&PBX_PORTEUR='.$properties['pbx_porteur'].
        '&PBX_REPONDRE_A='.$properties['pbx_repondre_a'].
        '&PBX_RETOUR='.$properties['pbx_retour'].
        '&PBX_EFFECTUE='.$properties['pbx_effectue'].
        '&PBX_ANNULE='.$properties['pbx_annule'].
        '&PBX_REFUSE='.$properties['pbx_refuse'].
        '&PBX_HASH=SHA512'.
        '&PBX_TIME='.$properties['dateTime'];
        $binKey = pack('H*', $properties['key']);
        $properties['hmac'] = strtoupper(hash_hmac('sha512', $msg, $binKey));

        if (isset($properties['is_sandbox_account']) && $properties['is_sandbox_account'] == 1) {
            $properties['server'] = 'https://preprod-tpeweb.e-transactions.fr/cgi/MYchoix_pagepaiement.cgi';
        } else {
            $properties['server'] = 'https://tpeweb.e-transactions.fr/cgi/MYchoix_pagepaiement.cgi';
        }

        return $properties;
    }

    public static function getPaymentsTypeFromApplication($application)
    {
        $paymentType = [];
        if (array_key_exists('full_amount', $application->properties)) {
            $paymentType['full-amount'] = 'Full Amount';
        }
        if (array_key_exists('fixed_amount', $application->properties)) {
            $paymentType['fixed-amount'] = 'Installments Fixed Amount';
        }
        if (array_key_exists('variable_amount', $application->properties)) {
            $paymentType['variable-amount'] = 'Installments Variable Amount';
        }

        return $paymentType;
    }

    public static function getPaymentsTypeFromApplicationWithFirstPayment($application)
    {
        $paymentType = [];
        if (array_key_exists('full_amount', $application->properties)) {
            $paymentType['full-amount'] = '<h3>Full Amount</h3>';
        }
        if (array_key_exists('fixed_amount', $application->properties)) {
            $paymentType['fixed-amount'] = '<h3>Installments</h3>'.
                $application->properties['fixed_amount']['amount_installments'].
                ' Installments';
        }
        if (array_key_exists('variable_amount', $application->properties)) {
            $paymentType['variable-amount'] = '<h3>Installments</h3>'.
                count($application->properties['variable_amount']['amount_installments']).
                ' Installments';
        }

        return $paymentType;
    }

    public static function getFixedInstallments($first_payment_date, $first_payment, $payment_type, $application)
    {
        $total_price = CartHelpers::getCartTotalPrice(CartHelpers::getCart($application));
        $total = $total_price['total'];

        if ($first_payment > 0) {
            $total = $total_price['total'] - $first_payment;
        }

        $amount = $total / $payment_type['amount_installments'];

        $installments = [];
        foreach (range(0, $payment_type['amount_installments'] - 1) as $index) {
            $month_to_add = self::monthToAdd($payment_type, $index);
            $item_date = Carbon::parse($first_payment_date)->addMonth($month_to_add);
            $installments[] = ['amount' => $amount, 'date' => $item_date->toDateString()];
        }

        return $installments;
    }

    public static function getVariableInstallments($first_payment, $payment_type, $application)
    {
        $total_price = CartHelpers::getCartTotalPrice(CartHelpers::getCart($application));
        $total = $total_price['total'];

        if ($first_payment > 0) {
            $total = $total_price['total'] - $first_payment;
        }

        $installments = [];
        foreach (range(0, count($payment_type['type']) - 1) as $index) {
            if ($payment_type['type'][$index] == 'percentage') {
                $amount = ((int) $payment_type['amount_installments'][$index] / 100) * $total;
            } else {
                $amount = $payment_type['amount_installments'];
            }

            $installments[] = ['amount' => $amount, 'date' => $payment_type['date'][$index]];
        }

        return $installments;
    }

    /**
     * @param $payment_type
     * @param $index
     * @return float|int
     */
    private static function monthToAdd($payment_type, $index)
    {
        if ($payment_type['frequency'] == 'montly') {
            $month_to_add = $index * 1;
        } elseif ($payment_type['frequency'] == 'three_month') {
            $month_to_add = $index * 3;
        }

        return $month_to_add;
    }

    /**
     * @param $application
     * @return \Illuminate\Support\Carbon|null
     */
    public static function getPaymentDate($payment_type, $application)
    {
        if ($payment_type['due_date'] == 'before_start_date') {
            $date = \App\Helpers\cart\CartHelpers::getFirstStartdate($application);
        } else {
            $date = now();
        }

        return $date;
    }

    public static function firstPayment($first_payment_options, $application)
    {
        $amount = 0;
        foreach ($first_payment_options as $option) {
            if ($option == 'fees') {
                $amount += $application->properties['application_fees'];
            } elseif ($option == 'addons') {
                $amount += CartHelpers::getAddonsAmount($application);
            }
        }

        return $amount;
    }

    public static function getPaymentGateways()
    {
        $application = new Application();
        dd($application->getPaymentGateways);
    }

    public static function addPayment($invoice, $student_id, $amount)
    {
        $payment = new InvoicePayments();
        $payment->uid = self::newPaymentNumber();
        $payment->amount_paid = $amount;
        $payment->status = 'Close';
        $payment->invoice_id = $invoice->id;
        $payment->student_id = $student_id;
        $payment->save();
    }

    public static function newPaymentNumber()
    {
        return rand(100000, 1000000);
    }
}
