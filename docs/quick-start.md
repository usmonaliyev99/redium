# Quick start

## Register trait

To begin your User model should use the `Laravel\Sanctum\HasApiTokens` trait:

```php
use Usmonaliyev\Redium\Traits\HasRediumTokens;

class User extends Authenticatable
{
    use HasRediumTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];
}
```

Please add your columns to `fillable` that you want to get when you use `auth()->user()` or `Auth::user()`.

## Register guard

Register redium guard in your `config/auth.php` file:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'redium' => [
        'driver' => 'redium',
        'provider' => 'users',
    ],
],
```

## Create new token

To create new token, you may use `createToken` method. API tokens are hashed using SHA-256 hashing before being stored
in Redis database.
You should display `plainTextToken` value to the user immediately after the token has been created:

Before creating token, make sure you have valid Redis server credentials in your `.env` file.

```php
use Illuminate\Http\Request;

Route::post('/create-token', function (Request $request) {
    $user = User::query()->first();
    $token = $user->createToken('Name for token');

    return ['token' => $token->plainTextToken];
});
```

## Protect your routes

Take plain token and send request with `Authorization: Bearer $plainTextToken` header.

To protect routes you should attach the redium authentication
guard to your API routes.

```php
use Illuminate\Http\Request;

Route::middleware('auth:redium')->get('/user', function (Request $request) {
    return $request->user();
});
```

If you want more, keep going...