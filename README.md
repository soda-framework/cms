# Soda CMS
A sweet PHP CMS solution built on Laravel!

## Installation
Install easily using the[Soda Installer](https://github.com/soda-framework/installer)or manually by following these steps:

#### 1. Create new Laravel Project

`composer create-project --prefer-dist laravel/laravel app-name "5.3.*"`

or

`laravel new app-name`

#### 2. Require Soda Framework

`cd app-name`

`composer require soda-framework/cms`

#### 3. Integrate into Laravel

Add package to providers in `/config/app.php`

```
'providers' => [
    Soda\Providers\SodaServiceProvider::class,
]
```

#### 4. Configure

`php artisan soda:setup`

or, manually set up database in your `.env` file.

#### 5. Migrate & Seed

(optional) `php artisan session:table`

`php artisan vendor:publish`

`php artisan optimize`

`php artisan soda:migrate`

`php artisan soda:seed`
