# Bambora SDK for API v1.0.4

Features:
* Payment
* Reporting
* Profiles
* Tokenization

Installation
-------
Use composer

    composer require enumit/bambora

Usage
-----
```php
$payment = \enumit\bambora\Gateway::getPaymentRequest('Your merchantId', 'Your API passcode', 'API version');

$response = $payment->makePayment([
    'order_number' => '123456',
    'amount' => '123.45',
    'payment_method' => 'card',
    'card' => [
        'number' => '400000001111',
        'name' => 'Card Holder',
        'expiry_month' => '08',
        'expiry_year' => '20',
        'cvd' => '123',
    ],
]);
```