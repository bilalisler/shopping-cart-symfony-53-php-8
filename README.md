# Symfony 5 Shopping App

Shopping Cart Example with php 8, symfony 5.3 and docker

## Requirements

- composer
- php >= 8.0
- docker
- mysql >= 5.7


## Installation

### Docker

- run `git clone git@github.com:bilalisler/shopping-cart-symfony-53-php-8.git` command
- run `cp .env .env.local` and type your environment variables, eg. valid mysql port for the Docker
- go to docker file and run `$ cd docker && docker-compose up`
- go into php container to run some following commands `$ docker exec -it shopping_php_fpm sh`
- install dependencies `$ composer install`
- run `$ php bin/console assets:install --symlink public`  
- create database `$ php bin/console doctrine:database:create`
- create tables  `$ php bin/console doctrine:schema:update --force`
- insert dummy data `$ php bin/console doctrine:fixtures:load`
- tap `exit` command and exit from container terminal
- enjoy that shit in web browser on `http://localhost:8088` URL

# Demo Users
 ###Admin;
 * Email: test@admin.com
 * Password: test

 ###Customer;
* Email: test@customer.com
* Password: test

## Tests
php ./vendor/bin/phpunit

## Demo URL ##
we can run the project on [http://127.0.0.1:8088](http://127.0.0.1:8088)

## Author
This project was made by BİLAL İŞLER
