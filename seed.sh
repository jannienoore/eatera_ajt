#!/bin/bash

# Script untuk migrate fresh dan seed database
cd /c/laragon/www/eatera_withfe

echo "Running migrations..."
php artisan migrate:fresh

echo "Seeding database..."
php artisan db:seed

echo "Done!"
