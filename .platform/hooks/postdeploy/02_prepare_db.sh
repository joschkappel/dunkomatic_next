#! /bin/bash
#cd /var/app/current/
php artisan storage:link
php artisan migrate:fresh
php artisan key:generate
php artisan db:seed --class=TestDatabaseSeeder
