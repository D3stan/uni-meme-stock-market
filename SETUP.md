# Installation Instructions for AlmaStreet Project

``` bash
cd almastreet
composer install
npm install
php artisan key:generate
php artisan migrate
rd public\storage
php artisan storage:link
composer run dev
```