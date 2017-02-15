---
title: Installation
layout: documentation
---
# Installation

* TOC
{:toc}

## Installation

Install easily using the [Soda Installer](https://github.com/soda-framework/installer) or manually by following these steps:

#### Via Laravel Installer

```php
laravel new app-name
```

#### Via Composer Create-Project

Alternatively, you may also install Laravel by issuing the Composer create-project command in your terminal:

```php
composer create-project --prefer-dist laravel/laravel app-name "5.3.*"
```

### Require Soda Framework

Require the Soda Framework by using Composer:

```php
cd app-name && composer require soda-framework/cms
```

### Integrate into Laravel

Add package to providers array in `/config/app.php`

```php
Soda\Providers\SodaServiceProvider::class,
```

### Configure

Rather than setting up your .env file manually, you may run the following command:

`php artisan soda:setup`

### Migrate & Seed

`php artisan session:table` (optional)

`php artisan vendor:publish && php artisan migrate && php artisan soda:seed`
