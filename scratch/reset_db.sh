#!/bin/sh

sudo truncate -s0 app/logs/dev.log
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
#php app/console doctrine:fixtures:load --no-interaction

php app/console fos:user:create smasterman shaun@masterman.com xxx --super-admin

