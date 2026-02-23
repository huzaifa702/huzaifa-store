#!/bin/bash

# Run migrations
php artisan migrate --force || true

# Seed database (only if tables are empty)
php artisan db:seed --force || true

# Cache config for performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Create storage link
php artisan storage:link || true

# Start Apache
apache2-foreground
