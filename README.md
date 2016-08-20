# Soda CMS
A sweet PHP CMS solution built on Laravel!

## Require via Composer
```
composer require soda-framework/cms
```

## Add package to providers in `/config/app.php`
```
'providers' => [
    Soda\Providers\SodaServiceProvider::class,
]
```

## Vendor publish
```
php artisan vendor:publish
```

## Run Migration
This will add in all the database elements required.
```
php artisan migrate --path /vendor/soda-framework/cms/database/migrations
```

## Run Seeder
To give you a head start at building your first website with Soda CMS!
```
php artisan db:seed --class=SodaSeeder
```

