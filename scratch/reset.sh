#!/bin/sh

sudo truncate -s0 app/logs/dev.log
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console doctrine:fixture:load

php app/console fos:user:create smasterman shaun@masterman.com xxx --super-admin
php app/console fos:user:create kpoulton kpoulton@fitchlearning.com changeme --super-admin

php app/console fos:user:create xuser xuser@masterman.com xxx
php app/console fos:user:promote xuser ROLE_USER

php app/console fos:user:create xfulluser xfulluser@masterman.com xxx
php app/console fos:user:promote xfulluser ROLE_FULL_USER

php app/console fos:user:create xeditor xeditor@masterman.com xxx
php app/console fos:user:promote xeditor ROLE_EDITOR

php app/console fos:user:create xfulleditor xfulleditor@masterman.com xxx
php app/console fos:user:promote xfulleditor ROLE_FULL_EDITOR

php app/console fos:user:create xsuper xsuper@masterman.com xxx --super-admin
