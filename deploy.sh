#!/bin/bash

cd /var/www/html/Hospital-Management-System-Laravel

echo "Pulling latest code..."
git reset --hard
git clean -df
git pull origin main

echo "Rebuilding containers..."
docker-compose down
docker-compose build
docker-compose up -d

echo "Running Laravel commands..."
docker exec laravel_app php artisan migrate --force
docker exec laravel_app php artisan config:cache
docker exec laravel_app php artisan route:cache
