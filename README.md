Tutor Directory Project
=======================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/69812bee-6fc9-4434-bbc0-b94a7b8531db/mini.png)](https://insight.sensiolabs.com/projects/69812bee-6fc9-4434-bbc0-b94a7b8531db)

This project was developed from the Spec below. I originally copy-pasted some bundles from the Twitter project - these 
probably don't need to have a common ancestor as these projects are unrelated and code sharing between them serves 
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

-- Addendum

* On the phone number fields can we have a ‘preferred’ tickbox – some tutors hate being called on their home phones unless it is an emergency. Either that or text notes by each entry.
* Rates. Sharon pointed out that as we change rates we should record this for audit purposes – she is right. Is there any way we can log changes as they happen and show who made the change and why?
* Can we replace the word ‘competency’ with ‘skill’ wherever it is used (sorry!)
* Can we replace the word ‘tutor’ with ‘trainer’ (sorry again and only need to change on the front-end!)

1) Installing it
----------------

Before you install, gather the following information - you will be asked to supply it during the installation:

Database - You can put the DB anywhere. I've used it locally, but if necessary it could be on any server that can be 
accessed from the host. You'll be asked for a database Driver, Host, Port, Name, User and Password. The system is only 
tested with MySQL, locally, but there's no reason it shouldn't work with any DB that has a pdo_ driver. The only 
explicit SQL statement at the time of writing lives in src/Fitch/TutorBundle/Entity/Repository/TutorRepository.php - 
everything else is done through Doctrine.   
 
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

Note "filestore" - This is a local filesystem used by gaufrette, as a file upload location while developing. In production
we use Amazon S3 - the details of which you should supply when initialising the system.  

Look here for details [Symfony Docs](http://symfony.com/doc/current/book/installation.html)

Clear the cache

    php app/console cache:clear --no-debug

Dump Assets in production (See installing SASS/Compass first)

    php app/console assetic:dump --no-debug

OR link assets in development

    php app/console assets:install --symlink

Check that Doctrine thinks the DB is setup properly for the production environment
    
    php app/console doctrine:ensure-production-settings

Check PHP environment

    php app/check.php
    
from a bare ubuntu install you will need to set the timezone to Europe/London

    sudo nano /etc/php5/cli/php.ini
    sudo nano /etc/php5/apache2/php.ini

You should also remove the memory limit (set it to -1) and also set an appropriate file size for file uploads. I don't 
know whats appropriate, but you should put some limit there that's probably bigger than the default.

Possibly you will need to update the php.ini for php-fpm also (See section 2)
        
Run the shell script scratch/reset_db.sh This performs the following steps (you can execute these manually if you want)

    Create the database
    
        php app/console doctrine:database:create
        php app/console doctrine:schema:create
    
    Load Fixtures 
    
        php app/console doctrine:fixtures:load
    
    Create a user with
    
        php app/console fos:user:create smasterman shaun@masterman.com <yourpassword> --super-admin
    
If at any time you want to reset the system, just run that script. Be warned that it just drops and recreates the 
database, so don't run it unless you are really sure about it.

2) Install php-fpm
------------------

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
dev machines - it may have just been me sudoing the wrong part of the install however.

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

8) Backups
----------

Two things need backing up - The database, and the S3 filestore. These backups need to be kept as a matching pair. The 
file management renames files and stores the renamed file as the 'key' field in the file DB, so if these get out of 
sync then the files are effectively useless as you wont know who they are for etc.
 
9) User Registration/Management
-------------------------------
 
### Register:

The system has implemented a "register" feature, allowing people to register to use it. On request this has been 
disabled and uses must be managed manually. 

### Adding Users through the interface:

Any person with super-admin roles (the account you created in Step 1) can add more users using the interface. 

A plain password must be supplied as part of the new user. The mechanism for updating a password once set, relies on 
the email address on the account, so make sure its set up properly. This is a potential gap that's created by not 
having users register and the email address being tested during that process. But whatevs :) There's a link to "reset 
my password" on the login page, that will allow a user to reset their password in the usual email verification way. 
If they haven't set up their email, or the server email isn't set up then you'll have to administer them manually which 
is a bit painful.

### Updating Passwords manually:

If you need to update someones password, on their behalf, there is a way, but its a bit convoluted:
 
    Log in as a super-admin
    go to the User Management page
    Switch to the user that you want to change. This should take you to the home page, as that user
    Choose "Profile" in the drop down User Menu (top right)
    Choose "Edit Password"
    Enter the new password, twice
    Hit back on the browser a few times till you get to the home page again (alternatively just get to the home page)
    Choose the "Eject" option at the bottom of the user menu, which should set you back as the Super admin you started on

To be honest if your doing this, then your not really doing things in the spirit of good security - so I don't feel too 
bad about it being eight steps :)   

Don't be tempted to delete the user and recreate just to change the password, else you'll kill any association to 
notes, files etc that have an Author field. 

### Account suspension

An account can be suspended by setting its 'enabled' flag to false/off in the interface.

### The command line

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
care of user administration too... up to you.

### Editing roles

Changing roles around is pretty simple - check the app/config/security.yml file you can see that a hierarchy of roles is 
defined at the top, and the URLs that can be accessed down the bottom - so add new roles at your leisure. They don't 
have to be hierarchical in nature. If you (for instance) want to create a role that allows access to the 'Operating 
Regions' crud interface separately from everything else, - just add ROLE_REGION_MANAGER at the top, and set the 
URL "/admin/region" to require that role down at the bottom of the security.yml file. Then add the role to the 
src/Fitch/UserBundle/Form/Type/NewUserType.php and src/Fitch/UserBundle/Form/Type/EditUserType.php files so that they 
appear as options in the User Management interface - and that's probably all that's needed. 
  
10) Translations
----------------
  
Most front end text is stored in locale files and outputted via the translation service. While its unlikely that the
software will be ported to French (etc) it could be by just duplicating those files, naming them fr instead of en. 
Translating all the text entries (but not the keys!) and set the install locale to fr. eh voila!
 
More importantly random requests to name things differently can be relatively quickly executed by just editing these 
files. There are occasional exceptions to text coming via translations - the most obvious is where its embedded in 
javascript files. With some effort this could be pulled out by dumping content into hidden divs. Its more effort than 
I can muster at this point however, and is likely to just add complexity rather than clarity.     
  
Translation files:

  src/Fitch/UserBundle/Resources/translations/FOSUserBundle.en.yml - User Management templates (Login, Profile, Emails etc) 
  src/Fitch/UserBundle/Resources/translations/messages.en.yml - User entity & User CRUD
  src/Fitch/TutorBundle/Resources/translations/messages.en.yml - Other entities & their CRUD 
  src/Fitch/FrontEndBundle/Resources/translations/FitchFrontEndBundle.en.yml - Menu Names
  src/Fitch/FrontEndBundle/Resources/translations/messages.en.yml - General Front End (Navigation, Errors etc)
  
11) Source Code
---------------
  
### Code Quality
This isn't the ERP codebase so I've tried to be a good code citizen. Dependency Injection is used everywhere and most 
controllers are trivially small. Sensio Insight finds a few issues - a lot of which fall into 'false positive' territory

1. For some reason it can't detect private method usage in the MenuBuilder class 
2. It thinks my form theme is too complicated - when symfony2.6 hits there is a bootstrap theme that probably means my 
themes can be destroyed
3. php-ref (a pretty var_dump) isn't locked to a specific release in composer - its also only used when I need to debug
 something so I don't really care
4. it doesnt like the fact that one of my Entity fields is named 'key' - it assumes its a 'sensitive' key 
5. .htaccess files could be moved server side (performance is not an issue this is early over-optimisation in my mind) 
 
Jetbrains PHPStorm displays "green" on all files. 
 
### Model Classes
I have as a conscious decision moved ALL entityManager stuff out of the controllers, preferring a "Model" class that 
handles the interaction with the persistence layer. This is a fairly typical thing to do if you are creating bundles for
external consumption (so that the end user can decide if they want to use ODM or ORM etc). It makes for very simple 
controllers in general so I do it even though the chances of these bundles being reused is close to zero.  

### Repository Classes 
There are Repository classes for all entities, the only one that's used is Tutor, but its a pattern I follow even if 
it results boilerplate code.
 
### Type-hinting 
I always type hint pulls from the DI container by creating a one line function that wraps the container->get()  

### Tests
Tests run against an SQLLite database that gets created on bootstrap, and replaced when you call restoreDatabase() - 
rather than this being in the setUp() for every test its called manually at the end of any test that disrupts the 
database.

### Data Fixtures
I use Alice and Faker to generate fake fixture data in YML files. Fixture files are numbered 10,20,30... etc to specify 
the dependency order. Non production fixtures are 500, 490, 480 etc and should only load in the Dev environment 
  
### Bundles
There are 4 bundles :

* "Common" contains any dependencies that the other three rely on (AbstractBase classes etc)
* "FrontEnd" contains all the general web-sitey stuff. All the javascript libraries, SCSS files etc. Also controllers for 
Menus, Header Bar etc.
* "Tutor" contains the main entities, their CRUD controllers and the controller for the main Profile and the Lookup table
* "User" contains all the user management stuff (and relies heavily on FOSUserBundle) 
  
  
EOF
