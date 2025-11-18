# Installation

You can install package via [Composer](https://getcomposer.org).

```bash
composer require usmonaliyev/redium
```

This is optional, if you want to change default ttl or redis connection, you can publish config file.

```bash
php artisan vendor:publish --tag=redium-config
```

This will create ```config/redium.php``` file in your project folder.
