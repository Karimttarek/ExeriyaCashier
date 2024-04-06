<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Client Tax Registration Number
    |--------------------------------------------------------------------------
    |
    | This value is the Tax Registration Number of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'registration_number' => env('REGISTRATION_NUMBER' , 'EG-123456789-'),

    /*
    |--------------------------------------------------------------------------
    | ETA Production End Points
    |--------------------------------------------------------------------------
    */
    'PRDapiBaseUrl' => env('PRDapiBaseUrl' , 'https://api.invoicing.eta.gov.eg/'),

    'PRDidSrvBaseUrl' => env('PRDidSrvBaseUrl' , 'https://id.eta.gov.eg/'),

    /*
    |--------------------------------------------------------------------------
    | ETA Preprod End Points
    |--------------------------------------------------------------------------
    */
    'PRDapiBaseUrl_preprod' => env('PRDapiBaseUrl' , 'https://api.preprod.invoicing.eta.gov.eg/'),

    'PRDidSrvBaseUrl_preprod' => env('PRDidSrvBaseUrl' , 'https://id.preprod.eta.gov.eg/'),


];
