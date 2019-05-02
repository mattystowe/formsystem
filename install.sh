#!/bin/bash
#A shell script to install all required packages set the environment
#migrate db and run initial seeds
#
echo "Getting required packages"
composer install --prefer-source --no-interaction
echo "Setting up env"
touch .env
cp .env.localdev .env
echo "Migrate and seed db"
php artisan migrate:refresh --seed
