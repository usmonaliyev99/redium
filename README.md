# redium

A high-performance laravel package powered by redis, inspired by sanctum.

## Why

When I'm new in [Laravel](https://laravel.com), I managed auth with [Sanctum](https://github.com/laravel/sanctum).

It works good, but I debugged my route, I saw queries which I sent to database.
There were few queries but one of them is not mine, I figured out it is Sanctum.

When application accept HTTP request, [Sanctum](https://github.com/laravel/sanctum) takes token from header and checks
it from database.
Actually I don't like this, I don't know it is good or no. If you know more than me, please text me.

Then I researched to set up [Sanctum](https://github.com/laravel/sanctum) with [Redis](https://redis.io/), I couldn't.

Then I know it is not possible after seeing code of Sanctum.

After all of that I decided to implement my own, [that](https://github.com/usmonaliyev99/laravel-redis-auth) is my first
package for auth.
In real word it works very good, but it has mistakes.

## About

Why did I name it Redium?

I took idea from Sanctum and Redis, That is why I named it like this .
That is why name of package is Redium

It does not send any query to database, it stores all data in redis.
This increase your application performance.

## Documentation

To learn more and how to use this package, please consult the [official documentation]().

## License

The MIT License (MIT).

Please see [License File](LICENSE.md) for more information.
