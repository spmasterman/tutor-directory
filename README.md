Tutor Directory Project
=======================

This project was developed from the Spec below. I originally copy-pasted some bundles from the Twitter project - these 
probably don't need to have a common ancestor as these projects are unrelated and code duplication between them serves 
little purpose.  

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

Before you install, gather the following information - you will be asked to supply it during the installation:

Database - You can put the DB anywhere. I've used it locally, but if necessary it could be on any server that can be 
accessed from the host. You'll be asked for a database Driver, Host, Port, Name, User and Password. The system is only 
tested with MySQL, locally, but there's no reason it shouldn't work with any DB that has a pdo_ driver.    
 
SMTP - I know sending email from an AWS server is complicated. But that's all I know :) I use mailcatcher in development
to test the process, but beyond that is more ops/admin than it makes sense for me to know. You will be asked for 
mailer transport, host, port, user, password  
 
locale - use "en" as a locale. Its the only locale that translations are set up for. (If you want to use another language
for the whole interface its all setup using symfony's translator service - all you need to do is supply translations for 
the files src/*Bundle/Resources/translations/* and supply a different locale. Im not sure this is ever likely to be 
required)

secret - throw some long complicated string in here. Its used in generating crsf tokens, and URL hashes so make sure 
you change it to something that ISNT committed to the repository, else all the security is very compromised.     

compass_bin - This is the location of the binary for compass/scss compilation. If you dont know it yet, leave the default 
value, then once you have set it up (section 4) edit the ap/config/paramaters.yml file and update the value.

AWS details - Files are stored on an S3 bucket - you will need the following details: bucketName, key and secretKey to 
access the bucket.
 
Once you have these details: 
 
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

Get composer and Update Vendors

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
We switch this out for s3 - the details of which you should supply when initialising the system. 

Look here for details [Symfony Docs](http://symfony.com/doc/current/book/installation.html)

Clear the cache

    php app/console cache:clear --no-debug

Dump Assets in production (See installing SASS/Compass first)

    php app/console assetic:dump --no-debug

OR link assets in development

    php app/console assets:install --symlink

Run the shell script scratch/reset_db.sh This performs the following steps (you can execute these manually if you want)

    Check that Doctrine thinks the DB is setup properly for the production environment
    
        php app/console doctrine:ensure-production-settings
    
    Create the database
    
        php app/console doctrine:database:create
        php app/console doctrine:schema:create
    
    Load Fixtures (initial data - TODO REMOVE TEST DATA FROM FIXTURES :o)
    
        php app/console doctrine:fixtures:load
    
    Create a user with
    
        php app/console fos:user:create smasterman shaun@masterman.com <yourpassword> --super-admin
    
If at any time you want to reset the system, just run that script. Be warned that it just drops and recreates the 
database, so don't run it unless you are really sure about it.

Check PHP environment

    php app/check.php

from a bare ubuntu install you will need to set the timezone to Europe/London

    sudo nano /etc/php5/cli/php.ini
    sudo nano /etc/php5/apache2/php.ini

You should also remove the memory limit (set it to -1) and also set an appropriate file size for file uploads. I don't 
know whats appropriate, but you should put some limit there that's probably bigger than the default.

Possibly you will need to update the php.ini for php-fpm also (See next section)

2 Install php-fpm
-----------------

The recommended way to run production Symfony2 apps is with PHP Fast Process Management.
 
What follows is how I've set it up on the dev server, however this seems to be software that's in a period of frequent
change so probably better that you Google how to set it up on the system you are installing. For instance, in the time 
between me setting up my Dev server libapache2-mod-fastcgi dropped the requirement that it listened on a TCP port 
rather than a Unix socket so these instructions are already possibly out of date.
  
For what its worth, the software runs OK just using the more traditional php/apache setup. Anyhow...   
  
    sudo apt-get install php5-fpm
    sudo apt-get install libapache2-mod-fastcgi
        
Set the www pool to listen on 127.0.0.1:9000 rather than the default Unix socket (this probably no longer necessary but 
not tested)
        
    sudo nano /etc/php5/fpm/pool.d/www.conf
    ...
    ;listen = /var/run/php5-fpm.sock
    listen = 127.0.0.1:9000
    ...
    
Use mod_proxy to pass incoming requests to PHP-FPM

    sudo a2enmod proxy proxy_fcgi
    
Edit your vhost to use the Process Proxy Match directive (see section 3)
    
     ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://127.0.0.1:9000/var/www/twitter-bot/web/$1
     
see here for more info [Symfony Docs](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html)

3) Setup the VHosts
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

For information on the ProxyProcessMatch entry see section 2. Short details, only include this line if your using 
php-fpm.

4) CSS/Frontend Development - installing SASS/Compass
-----------------------------------------------------

Compass is required for sass compilation. If you want to use something like capifony to bake the assets and only upload 
the production assets - go ahead. I haven't for time constraint reasons.  

    (possibly: sudo apt-get install ruby)
    sudo gem install compass

Dumping Assets through assetic may give you permission issues (its a bug in Compass support for assetic)
If you get an unthemed page, find the sass-cache folder in /tmp/ and empty it. Seemed to fix the problem for me on my
dev machines.

    php app/console assetic:dump --force
    
find the where the binary has been installed (it should tell you when it installs it) and replace the path (compass-bin) 
in app/config/parameters.yml     

5) Setup java
-------------

Assetic (more specifically some of the filters used by assetic) expects that java is available at /usr/bin/java this is
the default location - To check...

    which java

if it returns a pointer to /usr/bin/java - you are good to go. Unfortunately on a base install AWS machine provisioned
for this purpose, it wasn't installed. So...

    sudo apt-get install default-jdk

It's possible that we just need the runtime - I haven't tested - if you want a lighter install than the 90 megs of the
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
following to make use of it (this isn't a requirement, only if your developing this app some more at a later date and 
want to use a more recent version.) 

1. Update via composer (relax the version restriction in composer.json) This pulls the upstream copy into 
vendors/fortawesome/font-awesome/. I'm going to call this (source). We copy a few files into (dest) which is 
src/Fitch/DashboardBundle/Resources/

2. Copy all the font files from (source)/fonts to (dest)/public/fonts

3. Copy the contents of the (source)/scss/variables.scss file to (dest)/assets/scss/_fa_variables.scss except the first 
line which defines the font location - use the existing value

4. Check that they haven't included new scss files by comparing the (source)/scss/font-awesome.scss file with 
(dest)/assets/scss/_font-awesome.scss

5. Make Any edit to (dest)/scss/main.scss to trigger Compass to recompile the scss into css

6. re-link the assets via symlink (assets:install --symlink)

7) Performance in Production
----------------------------

1) Make sure you are using APC

2) Dump the autoloader (so that classes locations are known - need to redo this every pull however)
 
    php composer.phar dump-autoload --optimize

3) consider apc.stat=0 in php for very static installs 

8 Backups
---------

Two things need backing up - The database, and the S3 filestore. These backups need to be kept as a matching pair. The 
file management renames files and stores the renamed file as the 'key' field in the file DB, so if these get out of 
sync then the files are effectively useless as you wont know who they are for etc.
 
9 User Registration/Management
------------------------------
 
The system has implemented a "register" feature, allowing people to register to use it. On request this has been 
disabled and uses must be managed manually. Any person with super-admin roles (the account you created in Step 1) can 
add more users using the interface. A plain password must be supplied. The mechanism for updating a password, relies on 
the email address on the account, so make sure its set up. Without email support, all you can do is DELETE the user 
account and recreate it - which will allow you to set a new password.    

An account can be suspended by setting its 'enabled' flag to false/off in the interface.

Users can be setup from the command line:

    php app/console fos:user:create <username> <email> <yourpassword>
    
And then roles assigned:
    
    php app/console fos:user:promote <username> <role>
    
where role is 
    ROLE_USER for a read only user
    ROLE_EDITOR for someone who can edit the Public tutor details
    ROLE_ADMIN for someone who can edit the Private tutor details, and the lookup tables
    ROLE_SUPER_ADMIN for someone who has no restrictions. 
    
Don't give SUPER_ADMIN to anyone apart from people that know what they are doing, else you'll be restoring 
from a backup quite frequently. I would make it a DevOps only set of privileges. But that means DevOps must take 
care of user administration too.

Changing roles around is pretty simple - check the app/config/security.yml file you can see that a hierarchy of roles is 
defined at the top, and the URLs that can be accessed down the bottom - so add new roles at your leisure. They don't 
have to be hierarchical in nature. If you (for instance) want to create a role that allows access to the 'Operating 
Regions' crud interface separately from everything else, - just add ROLE_REGION_MANAGER at the top, and set the 
URL "/admin/region" to require that role down at the bottom of the security.yml file. Then add the role to the 
src/Fitch/UserBundle/Form/NewUserType.php and src/Fitch/UserBundle/Form/EditUserType.php files so that they appear as 
options in the User Management - and that's probably all that's needed. 
  

         