# Kata09: Back to the Checkout 😼

# Introduction

Implementation of "Kata09: Back to the Checkout"

http://codekata.com/kata/kata09-back-to-the-checkout/

# Technology Stack

- PHP 8.3
- PHPUnit 11.2

# Getting Started guide

### Building

`docker compose run --build --rm php-fpm composer install`

### Running tests

`docker compose run --rm php-fpm ./vendor/bin/phpunit --colors=always tests/CheckoutTest.php`