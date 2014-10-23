#!/bin/sh

sudo truncate -s0 app/logs/dev.log
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console khepin:yamlfixtures:load dev

php app/console fos:user:create smasterman shaun@masterman.com xxx --super-admin

