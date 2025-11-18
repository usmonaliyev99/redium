# Introduction

This page is full documentation of [Redium](https://usmonaliyev99.github.io/redium/) package.

Redium is high-performance authentication package specifically for API.
This secure token-based authentication system like [Sanctum](https://github.com/laravel/sanctum), but main difference.

**Everything is stored in Redis.**

- No SQL tables.
- No migrations.

## 🧩 Summary

- API-focused
- Fast Redis-based token storage
- Same security model as Sanctum
- Automatic token expiration powered by Redis
- Fastest rule management
- No database queries
- Revoking tokens

## 📦 Requirements

* PHP 8.2+
* Laravel 10+
* Redis server

## ❓ Why Redium?

When I'm new in [Laravel](https://laravel.com), I managed auth with Sanctum.

It works good, but I debugged my route, I saw queries which I sent to database.
There were few queries but one of them is not mine, I figured out it is Sanctum.

When application accept HTTP request, Sanctum takes token from header and checks
it from database.
Actually I don't like this, I don't know it is good or no. If you know more than me, please text me.

Then I researched to set up Sanctum with Redis, I couldn't.

Then I know it is not possible after seeing code of Sanctum.

After all of that I decided to implement my own, [that](https://github.com/usmonaliyev99/laravel-redis-auth) is my first
package for auth.
In real word it works very good, but it has mistakes.

## 💡 Solution

Why did I name it Redium?

I took idea from Sanctum and Redis, That is why I named it like this .
That is why name of package is Redium

It does not send any query to database, it stores all data in redis.
This increase your application performance.

