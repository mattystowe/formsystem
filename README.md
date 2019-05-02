Formsystem for shared use with flowtracker and opcentral


## Pre Requirements
- You need to have Composer installed on your local machine globally **BEFORE** you start.
- More information how to install and setup [here](https://getcomposer.org/doc/00-intro.md)



## Install
- In project directory root run ./install.sh


## Running locally
- In project directory root run ./run.sh


## Tests
- In project directory root run ./test.sh
- *This script handles the environment settings, sqlite db migrations and seeds*


## Other information
-More information at [laravel docs](https://laravel.com/docs/5.4)




# Deployments ci
- cp .env.ci .env
- php artisan migrate
- mkdir ./../releases
- zip -r ./../releases/www.zip ./
- Upload ../releases/www.zip to s3://formsystemdeployments/ci/
