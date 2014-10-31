Tutor Directory Project
=======================

This project is currently still being heavily developed. I've copy-pasted some bundles from the Twitter project - these 
probably don't need to have a common ancestor - these projects are unrelated and code duplication between them serves 
little purpose. If I find that I'm making the same edits in both projects more than once in a blue moon, I'll produce 
some independent bundle that they both can share.  

0) Spec
-------

Instructor Competency Database
 
We have a big faculty (permanent tutors) and associate base (consultants who we can call in when needed and pay to teach on a per day basis).
 
It is becoming clear that we are developing corporate amnesia about these people! We don’t know who we have available, what they are capable of doing, or what their terms of engagement are. So say a CRM, or a regional MD needs to find a tutor capable of teaching financial statement analysis in Asia, it is nigh on impossible for us to do so and we end up searching from scratch or looking over old course lists to find them. What we need is a database system to manage these individuals – a ‘little black book’ for our faculty and consultants. I have looked externally for a system to buy in without success.
 
Such a system can have a good, immediate commercial return as have made a number of different changes to the teams here over the last few months and everyone is trying to get their knowledge together.
 
The ‘old’ way of developing this would be to build an ERP function and tie it into existing functionality but this is not congruent with the concept of building more isolated systems that we are heading towards.
 
What I would like to have is the following simple, standalone system.
 
* A CRUD interface for storing information about tutors. I imagine the key information we need will be (I am imagining each category being a tab)
* Personal information (Name, address, home country, region, e-mail, phone, photo (not critical) etc.)
* Terms of engagement (Uploaded contract, rates, notes)
* Competencies. We need the ability to tag tutors with competencies – a competency is an arbitrary tag that searches for pre-existing tags AND a level (intern, graduate, practitioner).
* A search function that allows you to search for any tutors that match the search term across any field (outside of terms of engagement).
* An admin function that allows us to CRUD users of the system.
 
In terms of logging in because this is separated from the ERP the user auth will need to be local. I would suggest using the Federated User Management system the guys are developing here but not sure how far along that is. Might be worth investigating this.
 
The Terms of engagement tab needs to be locked to specific users. Because we are holding personal data the system needs to be secure (not ridiculously so but all the basics need to be right – no open URLs etc).

1) Installing it
----------------

Clone repo directly into /var/www

    cd /var/www
    git clone git@github.com:spmasterman/tutor-directory.git

Alternatively you can clone it somewhere else and symlink it if that's your thing.

First set the SYMFONY_ENV environment on the server

    sudo nano /etc/environment
    ...
    SYMFONY_ENV=prod

Close the shell, and reopen it. Check its set with

    printenv

Update Vendors

    php -r "readfile('https://getcomposer.org/installer');" | php
    ./composer.phar update

Set permissions:

1. see if you need to install ACL support

    mount | grep acl

2. if no results then install acl

        sudo apt-get install acl

    enable it on the root partition add ,acl to the root partition i.e.

        sudo nano /etc/fstab
        ...
        LABEL=cloudimg-rootfs   /        ext4   defaults,acl    0 0
        ...

    remount the root

        sudo mount -o remount /

    check its working

        mount | grep acl

3. if/once acl is installed:

        HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
        sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs filestore
        sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs filestore

Note the addition of filestore. This is a local filesystem used by gaufrette, as a file upload location. In production
We should be switching this out for an s3 or other backupable/offsite/cloud basedfilesystem. Gaufrette the system 
insulates our code from that.

Look here for details [Symfony Docs](http://symfony.com/doc/current/book/installation.html)

Clear the cache

    php app/console cache:clear --no-debug

Dump Assets in production (See installing SASS/Compass first)
TODO!! production servers shouldn't be compiling assets

    php app/console assetic:dump --no-debug

OR link assets in development

    php app/console assets:install --symlink

Check that Doctrine thinks the DB is setup properly for the production environment

    php app/console doctrine:ensure-production-settings

Create the database

    php app/console doctrine:database:create
    php app/console doctrine:schema:create

Load Fixtures (initial data - TODO REMOVE TEST DATA FROM FIXTURES :o)

    php app/console doctrine:fixtures:load

Create a user with

    php app/console fos:user:create smasterman shaun@masterman.com <yourpassword> --super-admin

Check PHP environment

    php app/check.php

from a bare ubuntu install you will need to set the timezone to Europe/London

    sudo nano /etc/php5/cli/php.ini
    sudo nano /etc/php5/apache2/php.ini

2 Install php-fpm
-----------------

The recomended way to run production Symfony apps is with PHP Fast Process Management 
To set it up, 
  
    sudo apt-get install php5-fpm
    sudo apt-get install libapache2-mod-fastcgi
        
Set the www pool to listen on 127.0.0.1:9000 rather than the default Unix socket (
        
    sudo nano /etc/php5/fpm/pool.d/www.conf
    ...
    ;listen = /var/run/php5-fpm.sock
    listen = 127.0.0.1:9000
    ...
    
Use mod_proxy to pass incoming requests to PHP-FPM

    sudo a2enmod proxy proxy_fcgi
    
Edit your vhost to use the Process Proxy Match directive (see section 4)
    
     ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://127.0.0.1:9000/var/www/twitter-bot/web/$1
     
see here for more info [Symfony Docs](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html)


3) CSS/Frontend Development - installing SASS/Compass
-----------------------------------------------------

Compass is required for sass compilation. Its shouldn't be required on Production systems, we should push a final asset 
file. However, at the moment that isn't happening, and the assets need to be dumped on production.

    (possibly: sudo apt-get install ruby)
    sudo gem install compass

Dumping Assets through assetic may give you permission issues (its a bug in Compass support for assetic)
If you get an unthemed page, find the sass-cache folder in /tmp/ and empty it. Seemed to fix the problem for me on my
dev machines.

    php app/console assetic:dump --force

4) Setup the VHosts
-------------------

create /etc/apache2/sites-available/tutor-directory.conf

(Change the ServerName accordingly)

    <VirtualHost *:80>
            ServerName tutor 
   
            ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://127.0.0.1:9000/var/www/tutor-directory/web/$1
    
            DocumentRoot /var/www/tutor-directory/web
            <Directory /var/www/tutor-directory/web>
                DirectoryIndex index.php
                Require all granted
            </Directory>
    
        ErrorLog /var/log/apache2/tutor_bot_error.log
        CustomLog /var/log/apache2/tutor_access.log combined
    </VirtualHost>

Use the FQDN of the site for the ServerName, obviously

enable it with:

    sudo a2ensite tutor-directory
    sudo service apache2 restart

For information on the ProxyProcessMatch entry see section 1.5

5) Setup java
-------------

TODO This shouldn't be required on Production if we are committing final assets, but were not doing that yet.

Assetic (more specifically some of the filters used by assetic) expects that java is available at /usr/bin/java this is
the default location - To check...

    which java

if it returns a pointer to /usr/bin/java - you are good to go. Unfortunately on a base install AWS machine provisioned
for this purpose, it isn't installed. So...

    sudo apt-get install default-jdk

Its possible that we just need the runtime - I haven't tested - if you want a lighter install than the 90 megs of the
jdk install default-jre instead.

If this server has or needs different versions of java then use the "alternatives" system to manage the symlink held
at /usr/bin/java. Namely:

    sudo update-alternatives --config java
    sudo update-alternatives --config javac

If there are multiple installations you can pick the one you want here. Much has been written about setting up and
managing java on an Ubuntu server - I won't repeat it.

6) Updating Font Awesome
------------------------

Font Awesome isn't hooked into the composer system very well. When they update it, and a new version arrives do the
following to make use of it:

1. Update via composer. This pulls the upstream copy into vendors/fortawesome/font-awesome/. I'm going to call this 
(source). We copy a few files into (dest) which is src/Fitch/DashboardBundle/Resources/

2. Copy all the font files from (source)/fonts to (dest)/public/fonts

3. Copy the contents of the (source)/scss/variables.scss file to (dest)/assets/scss/_fa_variables.scss except the first 
line which defines the font location - use the existing value

4. Check that they haven't included new scss files by comparing the (source)/scss/font-awesome.scss file with 
(dest)/assets/scss/_font-awesome.scss

5. Make Any edit to (dest)/scss/main.scss to trigger Compass to recompile the scss into css

6. re-link the assets via symlink (assets:install --symlink)

