# Club Manager

A management system for gyms and fitness clubs:
- book appointments and events
- schedule exercise classes and manage exercise class bookings
- manage members and track membership contracts
- Permit/Deny member visits (Check Ins) by card entry (bar code, qr code, or swipe card reader) or manual entry by an member of staff
- administration of venues, instructors, and membership contract types

## History
Inspired by MRBS (https://mrbs.sourceforge.io/), the first version was written in 2009, and was used to manage operations for a small independent fitness centre owned by my wife and I. Commercial systems were of course available, but back then they were slow and expensive so we wrote our own.

By 2011 most of the original MRBS code had been superceeded and I had ported the code to Zend Framework 1, with Postgres as the backend.

Sometime around 2015 I ported the code to Laravel 5.2, and added APIs so club members could book and pay for exercise classes online using a Wordpress plug-in from our public web site. 

We sold the gym in 2018, but I needed an excuse to experiment with Laravel 11 so here it is. 

## Caveats
Migrating from Laravel 5 to Laravel 11 was quite tedious. I guess I left it too long...
- The front end is still Jquery and Bootstrap. 
- The Wordpress API is not implemented in this version
- Direct Debit management and reporting are not implemented in this version

## Host Environment

Laravel 11 requires PHP 8.2.0 or later.
I use postgresql for the database, but any DB that supports jsonb data should work.

I'll add Docker compose examples later, in the meantime here is how to build manually on Debian 12:

### DB
```apt install postgresql```

As the default postgres superuser, create a role for use by laravel:

(Use your own values for db_user, db_pwd, and db_name!)


```
sudo su - postgres
psql
> CREATE ROLE <db_user> LOGIN;
> ALTER ROLE <db_user> PASSWORD '<db_pwd>';
> CREATE DATABASE <db_name> WITH OWNER <db_user>;
```

### PHP + NGINX

``` apt install nginx php-cli php-fpm php-pgsql php-curl php-xml php-mbstring php-zip php-intl```

Composer also needs git and zip for some of the dependencies:

``` apt install git zip unzip```

### SSL Certs
Issue an SSL certificate for local testing, copy PEM and KEY to /etc/nginx/ssl and chmod 400

Deploy nginx site config to /etc/nginx/sites-available, and link to sites-enabled

Deploy PHP FPM config to /etc/php/8.2/fpm/pool.d

Restart nginx and php8.2-fpm

### Composer
Download and install composer, make it globally available.

See https://getcomposer.org/download/ for details

```
./getcomposer.sh
sudo mv composer.phar /usr/local/bin/composer
```

### Laravel

```composer global require laravel/installer```

Edit .bashrc so PATH finds the laravel installer:

```export PATH=~/.config/composer/vendor/bin:$PATH```


### Deploy this project
```
cd /srv
git checkout https://github.com/dkxl/club-manager.git
cd club-manager
composer install
```

### Local configuration

Copy .env.example to .env, and edit with your database and app settings


### Database Seeding
```
cd /srv/club-manager
php artisan migrate
```

### Admin User
An administration user account is created by the database seeder. Username and password is defined
in ```/database/seeders/AdminSeeder.php```

Other application users cannot register themselves; use the admin user account to create additional
user accounts and to set their privileges.

Login as the admin user, and click on the ```Admin``` button to create additional user accounts and 
configure their roles, e.g. admin, staff, sales, or member. 



### Debugging with PHPStorm

```apt install php-xdebug```

On Debian, configure by editing /etc/php/8.2/mods-available/xdebug.ini, not php.ini:
```
    zend_extension=xdebug.so
    xdebug.mode=debug
    xdebug.client_host=172.16.103.2
    xdebug.client_port=9003
```

Configure project as per this guide:
https://www.jetbrains.com/help/phpstorm/debugging-with-phpstorm-ultimate-guide.html#setup-from-zero

