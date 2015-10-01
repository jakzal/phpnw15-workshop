# Loose coupling in practice (PHPNW 2015)

## Requirements

  * PHP 5.5 or higher;
  * PDO-SQLite PHP extension enabled;
  * and the [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html)
    (since part of the workshop is based on the [symfony-demo](https://github.com/symfony/symfony-demo) application).

You'll also need **git** to clone the repository and save your progress
during the workshop.

## Setting up

Clone the repository:

    git clone git@github.com:jakzal/phpnw15-workshop.git
    cd phpnw15-workshop

Run the following setup command and make sure it finishes with **no errors**:

    php setup.php

It's safe to run it multiple times.

Run the tests to make sure the app works fine:

    ./bin/phpunit -c app

Please set it up as soon as possible, and let me know if there's any issues
(my e-mail can be found in `composer.json`).

A day before the workshop, please pull the latest version of the repository
and run the setup script again:

    git pull
    php setup.php
    ./bin/phpunit -c app

## Starting the web server

To simplify the setup, we won't use a real web server during the workshop.
The one built into PHP will work just fine.
You can [start it with a Symfony command](http://symfony.com/doc/current/cookbook/web_server/built_in.html):

    php app/console server:run

