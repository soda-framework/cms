# sodacms
Soda cms package

##composer require
```
composer require sodacms/sodacms:1.1.*
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

## add middleware
To `/app/Http/Kernel.php`
```
$routeMiddleware = [
    //Existing middleware
    'soda.auth'=>\Soda\Middleware\Authenticate::class
]
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

