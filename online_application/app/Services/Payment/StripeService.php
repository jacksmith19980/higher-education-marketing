<?php

namespace App\Services\Payment;

use Stripe\Charge;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Token;

class StripeService implements PaymentService
{
    public function __construct($secret_api_key)
    {
        Stripe::setApiKey($secret_api_key);
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function addCustomer($customerDetailsAry): Customer
    {
        return Customer::create($customerDetailsAry);
    }

    public function paymentProcess($payment_details)
    {
        $card_details_array = [
            //'customer' => $customer_result->id,
            'amount'            => $payment_details['amount'] * 100,
            'currency'          => $payment_details['currency_code'],
            'description'       => $payment_details['item_name'],
            'source'            => $payment_details['token'],
            'metadata'          => [
                'invoice_id'    => $payment_details['invoice_id'],
            ],
        ];
        $result = Charge::create($card_details_array);

        return $result->jsonSerialize();
    }

    /**
     * @param \Stripe\Customer $customer_stripe
     * @param \Stripe\Plan $plan
     * @param string $currency
     * @param string $interval
     * @return array
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function addSubscription(
        Customer $customer_stripe,
        Plan $plan_stripe
    ): array {
        $result = Subscription::create([
            'customer' => $customer_stripe['id'],
            'items' => [['price' => $plan_stripe['id']]],
        ]);

        return $result->jsonSerialize();
    }

    public function createToken($details)
    {
        return Token::create([
            'card' => [
                'number' => $details['card_no'],
                'exp_month' => $details['ccExpiryMonth'],
                'exp_year' => $details['ccExpiryYear'],
                'cvc' => $details['cvvNumber'],
            ],
        ]);
    }

    /**
     * @param \App\Plan $plan
     * @param string $currency
     * @param string $interval
     * @return Plan
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function addPlan(\App\Plan $plan, string $currency = 'CAD', string $interval = 'month'): Plan
    {
        return \Stripe\Plan::create([
            'product' => [
                'name' => $plan->title,
            ],
            'amount'            => $plan->price * 100,
            'currency'          => $currency,
            'interval'          => $interval,
            'interval_count'    => 1,
        ]);
    }
}
