# XFS tracker tool with MySQL

XFS tracker tool is made up of the following things:

    eos-tracker-frontend <--> eos-tracker-api <--> MySQL <--> Redis <--> nodeos <--> blockchain
           ^                                                                ^
           |                                                                |
           |----------------------------------------------------------------|

## Redis

Install and start

    https://redis.io/download


## nodeos

Start nodeos (EOS daemon) with connection to mongodb named "EOStest"
by running the following cmd:

    cd nodeos

    pm2 start client_node_connect_to_testnet_mongo/script_client_node_mongo.sh
    or:
    pm2 start single_eosio_producer_mongo/script_eosio_producer_mongo.sh

## eos-tracker-api

https://github.com/EOSEssentials/EOSTracker-API

EOS Tracker API is a PHP Backend based on Symfony3 that connects to a MySQL database.

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


Config file of MySQL connection for eos-tracker-api

    // Specify MySQL URL and password
    app/config/parameters.yml.dist

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

    // API server listens at port 4201.
    // The port 4201 is specified in the nginx config file
    // /etc/nginx/conf.d/feesimpletracker.io.conf

    php bin/console server:run 127.0.0.1:4201

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
                walletUrl: 'https://feesimplewallet.io',
                votingUrl: 'https://eosportal.io',
                appName: 'XFS Tracker',
                logoUrl: '/assets/logo.png',
                apiUrl: 'https://feesimpletracker.io:444',
                blockchainUrl: 'http://127.0.0.1:8877'
             };
            ```

*Note:*

    Do not use `src/environments/environment.ts` as it's only for dev mode running with `ng serve`

*Note:*

    `apiUrl` is specified with https at port 444.
    The port 444 is specified in the nginx config file
    `/etc/nginx/conf.d/feesimpletracker.io.conf`.
    Any traffic to https at port 444 will be redirected to 127.0.0.1:4201


Installation

    sudo npm install -g @angular/cli@latest

    npm install

    // Critical to run the below cmd. Otherwise, cannot start server
    npm run postinstall

To start the frontend at specific IP:Port

    npm start

    or run the below cmd:
    pm2 start script_run_frontend.sh

To debug the frontend:

    npm run build
    pm2 restart <PID>
