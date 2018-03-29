# Mailer API

### Notes:
This application assumes different "owners" will have their own subscribers. An owner would be like a user.
Different owners can have the same subscribers, meaning 2 owners can both have the subscriber "user@email.com" as a subscriber
However, each owner will have their own "user@email.com" subscriber with a different subscriber id in the subscribers table.
For the purpose of this test assignment, we will forego the owners table and use owner with id 1 as the default owner of the subscribers.

The premise for fields is that there would be a values table that contains all the values for the different fields with a one to many relationship
using the fields id as the relationship between fields and values. The structure for values table would likely be something like
fields_id - unsigned int - indexed
value - varchar 255 - the code would handle type safety for the value based on the "type" in the fields table after a join.

For simplicity's sake, API authentication is not implemented. However, Laravel does provide api authentication through the API Auth middleware.

### Pre-installation Requirements:
You will need a webserver with php 7.1 or higher installed.
You will need an MySQL db installed with version 5.7.7 or higher.
Make sure your php executable is accessible from cmd/terminal.
Make sure your MySQL my.ini file has the following settings:

    # default character set and collation
    collation-server = utf8mb4_unicode_ci
    character-set-server = utf8mb4

    # utf8mb4 long key index
    innodb_large_prefix = 1
    innodb_file_format = barracuda
    innodb_file_format_max = barracuda
    innodb_file_per_table = 1

You will need to install composer. For instructions on installing composer for your operating system, go to:

    https://getcomposer.org/download/

### Installation
Create a database that will be used for the API.
Edit the .env file to reflect your MySQL connection settings.
Navigate to the root directory of this project in your local system on a cmd/terminal, then run

    composer install
    php artisan migrate
    php artisan db:seed

### API Paths
SUBSCRIBERS:
    GET
        /api/subscriber => gets an array of all subscribers in DB
        /api/subscriber/{id} => gets the subscriber with specified id
    PUT
        /api/subscriber/{id} => updates the subscriber with specified id using json values in body
        /api/subscriber/activte/{id} => updates state value to activated for subscriber with specified id
    POST
        /api/subscriber => creates new subscriber
    DELETE
        /api/subscriber/{id} => Deletes subscriber with specified id.

FIELDS:
    GET
        /api/field => gets an array of all fields in DB
        /api/field/{id} => gets the field with specified id
    PUT
        /api/field/{id} => updates the field with specified id using json values in body
    POST
        /api/field => creates new field
    DELETE
        /api/field/{id} => Deletes field with specified id.

### Unit testing
To run unit testing, execute command:

    ./vendor/bin/phpunit