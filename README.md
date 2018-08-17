# message-bird-sender
This is a experimental repository to utilize the message bird sdk and api

# Installation without Docker
```BASH
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install --prefer-dist -o
```
Set environment variable `APPLICATION_ENV = 'development'`  
Change the API key in: config/development.php `__your_key_here__`


# Installation with Docker
```BASH
php -r "readfile('https://getcomposer.org/installer');" | php
docker-compose build php
docker-compose run php php composer.phar install --prefer-dist -o
```
Change the API key in: config/development.php `__your_key_here__`

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

With the sender service running, you can start send messages:
```BASH
# start the web service
docker-compose up -d

# short messages
curl -X POST 'http://localhost:8080/' -F 'recipients=5551996723064' -F 'body=Lorem ipsum'

# or long messages
curl -X POST 'http://localhost:8080/' -F 'recipients=5551996723064' -F 'body=Lorem ipsum \
dolor sit amet, consectetur adipisicing elit. Quaerat ipsum ea molestiae cum esse voluptates \
mollitia perferendis rerum! Voluptatum ratione, reiciendis officiis perferendis id tempore! \
Quia veritatis fuga eligendi similique.'

# or many recips 
curl -X POST 'http://localhost:8080/' \
-F 'recipients[0]=5551996723064' \
-F 'recipients[1]=5551996723064' \
-F 'body=Lorem ipsum'
```

# Tests
```BASH
docker exec -it message-bird-web php vendor/bin/phpunit
```
