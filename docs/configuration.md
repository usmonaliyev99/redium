# Configuration

## 🔧 Change default connection

By default, token will be created in 0 database of Redis server.

If you want to change database for tokens, you have to create new redis database connection.
All redis connections are in your `config/database.php` file, add new one for example:

```php

'redis' => [
    ...
    'default' => [
        ...
    ],
    
   'redium' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'username' => env('REDIS_USERNAME'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => '9',
        'max_retries' => env('REDIS_MAX_RETRIES', 3),
        'backoff_algorithm' => env('REDIS_BACKOFF_ALGORITHM', 'decorrelated_jitter'),
        'backoff_base' => env('REDIS_BACKOFF_BASE', 100),
        'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000),
    ], 
]
```

Just copy and paste `default` connection and change name of it also `database` option, in this example I use 9 database.

After creating connection, you can change Redium connection in `config/redium.php` file.

```php
/*
|--------------------------------------------------------------------------
| Redis database
|--------------------------------------------------------------------------
|
| This value is the name of your redis connection in your app/database.php file
|
*/

'connection' => 'redium',
```

Then it stores and reads tokens from 9 database.

## ⌛ Change default ttl

If you do not give `expiresAt` during creating token, Redium takes default `ttl` (Time to Live).
If it is null, token won't expire.

You can change it, you should put how much minutes token should live in `ttl` section.

```php
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

'ttl' => 1440,
```

Good willing, There will be few configs, go ahead.