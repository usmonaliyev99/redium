<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redis database
    |--------------------------------------------------------------------------
    |
    | This value is the name of your redis connection in your app/database.php file
    |
    */

    'connection' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Expiration seconds
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until a token will be deleted.
    | This will override any values set in the token's "expires_at" attribute.
    |
    | For example: 1440 min is 1 day
    |
    */

    'ttl' => null,

];
