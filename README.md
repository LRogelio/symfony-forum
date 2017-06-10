symfony-forum
=============

Forum built with Symfony 3.

## Setup

- Install Vagrant and Virtualbox.
- Install the vagrant box [Ubuntu 16.04 LEMP box](https://github.com/rod86/ubuntu-lemp-box)
- Setup the host file using the generatehost script (see box details) and use these details.

|         |                |
| ------- | -------------- |
| **Project name**| symfony-forum |
| **Project domain**| symfony-forum.dev |
| **Project type**| symfony |
   
    
- Edit the hosts file and add

```
    192.168.56.105  symfony-forum.dev
```

- Clone this project inside the *www* folder

- Connect via SSH to the box and run

```
   $ cd /var/www/symfony-forum
   
   # Create database
   $ php bin/console doctrine:database:create

   # Create Tables
   $ php bin/console doctrine:schema:update --force

   # Load test data
   $ php bin/console doctrine:fixtures:load -n
```