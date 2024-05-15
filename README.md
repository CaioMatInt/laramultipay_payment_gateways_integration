[![Project Status: WIP – Initial development is in progress, but there has not yet been a stable, usable release suitable for the public.](https://www.repostatus.org/badges/latest/wip.svg)](https://www.repostatus.org/#wip)

# Attention: This project is in early development stage

# Description

This project integrates multiple payment platforms, allowing for the processing of different payment methods. It supports gateways like Stripe, Gerencianet, Flutterwave, and Authorize.net, and handles payments via international credit cards, PIX (Brazil), and boleto (Brazil). 

The system manages multiple currencies and is suitable for businesses with complex organizational structures. The architecture is designed with flexibility in mind, making it straightforward to incorporate new payment methods. 

## Database diagram
TBE

## System Architecture
![image](https://github.com/CaioMatInt/payment_challenge/assets/40992883/3ba42426-2e5c-4b7e-9f67-66550e457b20)

### Building it with

![image description](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)![image description](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)![Redis](https://img.shields.io/badge/redis-%23DD0031.svg?style=for-the-badge&logo=redis&logoColor=white)![SQLite](https://img.shields.io/badge/sqlite-%2307405e.svg?style=for-the-badge&logo=sqlite&logoColor=white)

## Postman Collection
TBE

## Tests 
<img src="https://pestphp.com/www/assets/logo.svg" width="92.5" height="28">

The tests are being carried out with the [PEST framework](https://pestphp.com/) for Laravel. You can run them by executing "php artisan test" in the root folder. The tests can be found in the "app/tests" directory.
