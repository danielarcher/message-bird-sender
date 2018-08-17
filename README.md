# message-bird-sender
This is a experimental repository to utilize the message bird sdk and api

# Installation without Docker
```BASH
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install --prefer-dist -o
```

# Installation with Docker
```BASH
php -r "readfile('https://getcomposer.org/installer');" | php
docker-compose build php
docker-compose up -d
docker-compose run php php composer.phar install --prefer-dist -o
```
Access: [localhost:8080](localhost:8080)

# Requirements
- php 7.2
- ext-mbstring
- ext-curl
- ext-sysvmsg

# 3rd party libraries
- phpunit/phpunit
- messagebird/php-rest-api

# Running Application
First of all, we will run the **sender service**.

```BASH
docker exec -it message-bird-web php console/sender.php
```

# Tests
```BASH
docker exec -it message-bird-web php vendor/bin/phpunit --bootstrap=tests/bootstrap.php tests
```
