#!/bin/sh

git clone https://github.com/webignition/web-resource-cache.git
cd web-resource-cache
chmod +x ./bin/console

composer install

./bin/console doctrine:migrations:migrate --no-interaction

composer require symfony/web-server-bundle
./bin/console server:start
