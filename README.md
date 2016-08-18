# sodacms
Soda cms package

## composer require
```
composer require sodacms/sodacms:1.1.*
```

## add package to providers
in /config/app.php
```
'providers' => [
    //Existing providers
    Soda\Providers\SodaServiceProvider::class,
]
```

## add alias
in /config/app.php
```
'aliases' => [
    //Existing aliases
    'Soda' => \Soda\Facades\Soda::class,
]
```

## vendor publish
```
php artisan vendor:publish
```


## set database
In your .env file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tst
DB_USERNAME=root
DB_PASSWORD=root
```

## Run Migration
This should add in all the database elements.
```
php artisan migrate --path /vendor/sodacms/sodacms/database/migrations
```

## Run Seeder
```
php artisan db:seed --class=SodaSeeder
```

