---
title: Installation
layout: documentation
---

# Installation {#toc}

* TOC
{:toc}

## [Installation](#installation) {#installation}

Install easily using the [Soda Installer](https://github.com/soda-framework/installer) or manually by following these steps:

#### Via Laravel Installer

```bash
laravel new app-name
```

#### Via Composer Create-Project

Alternatively, you may also install Laravel by issuing the Composer create-project command in your terminal:

```bash
composer create-project --prefer-dist laravel/laravel app-name
```

### [Require Soda Framework](#require-soda-framework) {#require-soda-framework}

Require the Soda Framework by using Composer:

```bash
cd app-name && composer require soda-framework/cms
```

### [Integrate into Laravel](#integrate-into-laravel) {#integrate-into-laravel}

Add package to providers array in `/config/app.php`

```php
Soda\Providers\SodaServiceProvider::class,
```

### [Configure](#configure) {#configure}

Rather than setting up your .env file manually, you may run the following command:

`php artisan soda:setup`

This command will guide you through setting up your database environment details, creating the database (if necessary).

### [Migrate and seed](#migrate-and-seed) {#migrate-and-seed}

Finally, migrate and seed your database with the following commands:

`php artisan session:table` (optional)

`php artisan vendor:publish && php artisan migrate && php artisan soda:seed`

### [Login](#login) {#login}

You will now be able to log in to your CMS using the following login details:

Login at: http://app-name.dev/cms

Username: admin@admin.com

Password: admin
