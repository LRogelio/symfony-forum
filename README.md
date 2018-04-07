symfony-forum
=============

Forum built with Symfony 3.

## Setup

- Install Vagrant and Virtualbox.
- Install the vagrant box [Ubuntu 16.04 LEMP box](https://github.com/rod86/ubuntu-lemp-box)
- Connect via SSH into the vagrant box and run

```
$ sudo bin/generatehost.sh -h dev.symfony-forum.com -t symfony
```

- Add the below line in the hosts file of your host machine

```
    192.168.56.105  dev.symfony-forum.com
```

- Clone this repository inside the *www* folder

```
    $ cd ubuntu-lemp-box/www
    $ git clone https://github.com/rod86/symfony-forum.git dev.symfony-forum.com
```

- Setup the *app/config/parameters.yml* file.

- Connect via SSH to the box and run

```
   $ cd /var/www/dev.symfony-forum.com

   # Install composer dependencies
   $ composer install

   # Create database
   $ php bin/console doctrine:database:create

   # Create Tables
   $ php bin/console doctrine:schema:update --force

   # Load test data
   $ php bin/console doctrine:fixtures:load -n
```
