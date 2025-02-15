<?php

use Luigel\Paymongo\Facades\Paymongo;
use Luigel\Paymongo\Tests\BaseTestCase;
use Luigel\Paymongo\Traits\Request;

uses(BaseTestCase::class, Request::class)
    ->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getTestCardWithout3dSecure()
{
    return '4343434343434345';
}

function getTestCardLast4()
{
    return '4345';
}

function createToken()
{
    return Paymongo::token()->create([
        'number' => getTestCardWithout3dSecure(),
        'exp_month' => 12,
        'exp_year' => 25,
        'cvc' => '123',
        'billing' => [
            'address' => [
                'line1' => 'Test Address',
                'city' => 'Cebu City',
                'postal_code' => '6000',
                'country' => 'PH',
            ],
            'name' => 'Rigel Kent Carbonel',
            'email' => 'rigel20.kent@gmail.com',
            'phone' => '928392893',
        ],
    ]);
}

function createPaymentIntent()
{
    return Paymongo::paymentIntent()->create([
        'amount' => 100,
        'payment_method_allowed' => [
            'card', 'paymaya',
        ],
        'payment_method_options' => [
            'card' => [
                'request_three_d_secure' => 'automatic',
            ],
        ],
        'description' => 'This is a test payment intent',
        'statement_descriptor' => 'LUIGEL STORE',
        'currency' => 'PHP',
    ]);
}

function createSource($type = 'gcash')
{
    return Paymongo::source()->create([
        'type' => $type,
        'amount' => 100.00,
        'currency' => 'PHP',
        'redirect' => [
            'success' => 'http://localhost/success',
            'failed' => 'http://localhost/failed',
        ],
    ]);
}

function createRequest(
    $method,
    $content,
    $uri = '/test',
    $server = ['CONTENT_TYPE' => 'application/json'],
    $parameters = [],
    $cookies = [],
    $files = []
) {
    $request = new \Illuminate\Http\Request();

    return $request->createFromBase(\Symfony\Component\HttpFoundation\Request::create($uri, $method, $parameters, $cookies, $files, $server, $content));
}
