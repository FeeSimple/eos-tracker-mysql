# XFS tracker tool with MySQL

XFS tracker tool is made up of the following things:

    eos-tracker-frontend <--> eos-tracker-api <--> MySQL <--> Redis <--> nodeos

## Redis

Install and start

    https://redis.io/download

## Mongodb

By default on Ubuntu, mongodb is compiled and built as plugin of nodeos.
To determine if mongodb is supported by nodeos, run the following cmd and
check if "--mongodb-uri" option available

Installation dir of mongodb:
    ~/opt/mongodb

Config file of mongodb:

    ~/opt/mongodb/mongod.conf

Command to start mongod (MongoDB daemon):

    ~/opt/mongodb/bin/mongod -f ~/opt/mongodb/mongod.conf &


Or run the provided script "script_mongod.sh" in the folder "mongo"

View log output of mongod:

    tail -f ~/opt/mongodb/log/mongodb.log

By default, mongod is listenning at localhost:27017

Interact with mongo database:
Ref link: https://docs.mongodb.com/manual/reference/mongo-shell

```
    ~/opt/mongodb/bin/mongo

    show dbs    

    use EOStest

    show tables

    db.transactions.count()
```

## nodeos

Start nodeos (XFS daemon) with connection to mongodb named "EOStest"
by running the following cmd:

    cd nodeos

    pm2 start client_node_connect_to_testnet_mongo/script_client_node_mongo.sh
    or:
    pm2 start single_eosio_producer_mongo/script_eosio_producer_mongo.sh

## eos-tracker-api

https://github.com/EOSEssentials/EOSTracker-API

XFS Tracker API is a PHP Backend based on Symfony3 that connects to a MongoDB database.

First, install the below dependencies:

Install PHP 7.2 (Ubuntu)

    sudo add-apt-repository ppa:ondrej/php
    sudo apt-get update
    sudo apt-get install php7.2

Install PHP MongoDB Driver (Ubuntu)

    sudo apt-get install php7.2-mongodb

Install PHP MySQL Driver (Ubuntu)

    // https://stackoverflow.com/questions/32728860/php-7-rc3-how-to-install-missing-mysql-pdo
    sudo apt-get install php7.2-mysql
    sudo phpenmod pdo_mysql

Install others (Ubuntu)

    sudo apt-get install php7.2-cli php7.2-xml
    sudo apt-get install php7.2-mbstring php7.2-intl php7.2-redis -y

 Install composer

    https://getcomposer.org/download/

Install PHP 7.2 (Mac)

    brew install php72

Install others (Mac)

    // http://php.net/manual/en/mongodb.installation.pecl.php
    sudo pecl install mongodb


Install dependencies with composer

    composer update
    composer install

Config file of MongDB for eos-tracker-api

    app/config/parameters.yml

        # This file is auto-generated during the composer install parameters:
        secret: 123
        mongodb_server: 'mongodb://localhost:27017'
        db_name: EOStest

Config file of MySQL for eos-tracker-api

    // Specify MySQL URL and password
    app/config/parameters.yml

        # This file is auto-generated during the composer install parameters:
        secret: 123
        env(DB_URL): 'mysql://root@localhost/eos'
        env(DB_PASSWORD): 'feesimple'
        env(REDIS_URL): 'redis://root@localhost:6379'


    // Use the above-specified MySQL URL and password
    app/config/config.yml

        ....

        doctrine:
             dbal:
                 url: "%env(DB_URL)%"
                 driver:   pdo_mysql
                 server_version: 5.7
                 charset:  UTF8
                 password: "%env(DB_PASSWORD)%"

        ....


To start:

    php bin/console server:run

Or run this cmd:

    pm2 start script_tracker_api.sh


## eos-tracker-frontend

https://github.com/EOSEssentials/EOSTracker

XFS Tracker is a Frontend based on Angular4 that connects to XFS Tracker API.

Config file of eos-tracker-frontend for interacting with eos-tracker-api:

        src/environments/environment.prod.ts

            ```
            export const environment = {
            production: true,
            walletUrl: 'http://138.197.194.220:3000',
            votingUrl: 'https://eosportal.io',
            appName: 'XFS Tracker',
            logoUrl: '/assets/logo.png',
            apiUrl: 'http://127.0.0.1:4201',
            blockchainUrl: 'http://138.197.194.220:8877'
            };
            ```

        *Note:* 

        Do not use `src/environments/environment.ts` as it's only for dev mode running with `ng serve`

Installation

    sudo npm install -g @angular/cli@latest

    npm install

    // Critical to run the below cmd. Otherwise, cannot start server
    npm run postinstall

To start the frontend at specific IP:Port

    npm run start

    or run the below cmd:
    pm2 start script_run_frontend.sh
