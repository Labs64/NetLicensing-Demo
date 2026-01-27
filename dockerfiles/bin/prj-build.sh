#!/bin/bash

set -e

# Install dependencies
composer install --prefer-dist --no-interaction --no-security-blocking

# Generate application key
php artisan key:generate
php artisan config:clear

# Verify environment config
cat .env

# Install dependencies
npm ci
npm run dev

# Execute PHPUnit tests
vendor/bin/phpunit
