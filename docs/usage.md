# Usage

I'm going to tell you, there are few example and this package similar with Sanctum.

That is why you can understand them easily.

## 🚪 Simple login

```php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

Route::post('/redium/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $accesss = $user->createToken($request->device_name);
    
    return response()->json(['token' => $access->plainTextToken]);
});
```

## 🧩 Token abilities

Redium allows you to assign "abilities" to tokens.

```php
$abilities = ['users.show', 'posts.show', 'employees.show'];
$accesss = $user->createToken($request->device_name, $abilities);
```

When handling incoming request authenticated by Redium, you may determine if token has a given ability using the
`tokenCan` or `tokenCant` methods:

```php
if ($user->tokenCan('users.show')) {
    // ...
}

if ($user->tokenCant('users.delete')) {
    // ...
}
```

## 👋 Sign out

You can sing out by using `delete` function of your `currentAccessToken` function:

```php
$user->currentAccessToken()->delete();
```

It will delete current token from Redis database.

## 🧹 Revoking tokens

You may "revoke" tokens by deleting them from Redis server using `tokens` function that is provided by
`Usmonaliyev\Redium\Traits\HasRediumTokens` trait.

```php
$tokens = $user->tokens();

array_map(fn ($token) => $token->delete(), $tokens);
```

If you want to sign out all sessions, you have to delete them one by one.
Maybe one day I will one function for this job.

## 🔐 Protecting routes

We only talked about creating tokens, delete them. Now we see how we can protect our routes:

As previously documented, you may protect routes so that all incoming requests must be authenticated by attaching the
`redium` authentication guard to the routes:

```php
Route::middleware('auth:redium')->get('/user', function (Request $request) {
    return $request->user();
});
```

If you have a lot of routes, you should use `group` function of Route class:

```php
Route::middleware('auth:redium')->group(function () {
    ...
})
```

If error is coming out like "Auth guard [redium] is not defined.", go to `config/auth.php` file and register `redium`
guard.