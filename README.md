# Paymob Laravel

![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/mgamal/paymob-laravel/php)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mgamal/paymob-laravel.svg?style=flat-square)](https://packagist.org/packages/mgamal/paymob-laravel)
[![CI](https://github.com/mgamal92/paymob-laravel/actions/workflows/ci.yml/badge.svg)](https://github.com/mgamal92/paymob-laravel/actions/workflows/ci.yml)
![GitHub](https://img.shields.io/github/license/mgamal92/paymob-laravel)

This is an integration between Laravel framework and Paymob API to make it easier for developers to use Paymob functionalities in their applications.

## Installation

You can install the package via composer:

```bash
composer require mgamal/paymob-laravel
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag=config --provider="MG\Paymob\PaymobServiceProvider"
```

## Usage
- Set Paymob credentials
```bash
PAYMOB_API_KEY        = xxxxxxxxxxxxxxxxxxxxxx
PAYMOB_INTEGRATION_ID = xxxxxx
PAYMOB_IFRAME_ID      = xxxxx
PAYMOB_HMAC_SECRET    = xxxxxxxxx
```
Reference: [Paymob integration](https://docs.paymob.com/docs/payment-integrations)


### 
- Prepare order items
- Prepare billing data
- Prepare total order
- Make payment with the full details of the order: items, billing data and total order.

#### **Example**

```php
use MG\Paymob\Paymob;

public function checkout(){
    // Prepare order items
    $orderItems = [
        [
            'name'         => 'Product x',
            'amount_cents' => 100,
            'description'  => 'Product description',
            'quantity'     => 1
        ]
    ];
    
    // Prepare billing data: Fill empty keys with 'N/A'; required!
    $billingData = [
        'first_name'      => 'John',
        'last_name'       => 'Doe',
        'email'           => 'someone@example.com',
        'phone_number'    => '+1xxxxxxxx',
        'apartment'       => 'N/A',
        'floor'           => 'N/A',
        'building'        => 'N/A',
        'street'          => 'N/A',
        'city'            => 'N/A',
        'shipping_method' => 'N/A',
        'country'         => 'N/A',
        'state'           => 'N/A',
    ];
    
    // Prepare order itself
    $orderToPrepare['amount_cents']      = 10;
    $orderToPrepare['merchant_order_id'] = 101;
    $orderToPrepare['items']             = $orderItems;
    $orderToPrepare['billing_data']      = $billingData;

    $payment = app(Paymob::class);
    // Get payment URL
    $paymentUrl = $payment->makePayment($orderToPrepare);

    return $paymentUrl;
}

```
