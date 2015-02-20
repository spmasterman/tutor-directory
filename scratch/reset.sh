#!/bin/sh

sudo truncate -s0 app/logs/dev.log
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console doctrine:fixture:load

php app/console fos:user:create smasterman shaun@masterman.com xxx --super-admin
php app/console fos:user:create kpoulton kpoulton@fitchlearning.com changeme --super-admin

php app/console fos:user:create xreadonly xuser@masterman.com xxx
php app/console fos:user:promote xreadonly ROLE_USER

php app/console fos:user:create xreadonlyfull xaccounts@masterman.com xxx
php app/console fos:user:promote xreadonlyfull ROLE_SENSITIVE_DATA

php app/console fos:user:create xeditor xeditor@masterman.com xxx
php app/console fos:user:promote xeditor ROLE_EDITOR

php app/console fos:user:create xeditorfull xfulleditor@masterman.com xxx
php app/console fos:user:promote xeditorfull ROLE_EDITOR
php app/console fos:user:promote xeditorfull ROLE_SENSITIVE_DATA

php app/console fos:user:create xadmin xadmin@masterman.com xxx
php app/console fos:user:promote xadmin ROLE_ADMIN

php app/console fos:user:create xsuper xsuper@masterman.com xxx --super-admin
