<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paystack' => [
        'baseurl' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
        'pubtestkey' => env('PAYSTACK_PUBTEST_KEY', 'pk_test_477c5f86ebda289ab39b1c996b9b992193592b24'),
        'pubtestsecret' => env('PAYSTACK_SECRET_TEST_KEY', 'sk_test_9ce0f084b067166ec80630be3fd4949d86a0779e'),
        'testhook' => env('PAYSTACK_LOCALHOOK_URL', 'http://127.0.0.1:8000/api/user/booking/payment/callback'),
    ],

];
