#!/bin/sh

sudo truncate -s0 app/logs/dev.log
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force

php app/console fos:user:create smasterman shaun@masterman.com xxx --super-admin
php app/console fos:user:create xuser shaun@masterman.com xxx
php app/console fos:user:promote xuser ROLE_USER

php app/console fos:user:create xeditorshaun@masterman.com xxx
php app/console fos:user:promote xeditor ROLE_EDITOR

php app/console fos:user:create xadmin shaun@masterman.com xxx
php app/console fos:user:promote xadmin ROLE_ADMIN


