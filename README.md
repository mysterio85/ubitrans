##  Test technique web back

###  Installation
To install, follow the following steps

#### Docker ( environnement ) 
* docker-compose build 
* docker-compose up -d 

#### Install symfony 4 with composer 
* docker-compose exec php composer install

#### Create Database and tables 
##### dev : 
* docker-compose exec php bin/console d:d:c --env=dev
* docker-compose exec php bin/console d:s:u --force --env=dev 

##### test : 
* docker-compose exec php bin/console d:d:c --env=test
* docker-compose exec php bin/console d:s:u --force --env=test

#### Fixture to dev 
* docker-compose exec php bin/console hautelook:fixtures:load

####  Test 
* docker-compose exec php bin/phpunit


#### Liens 
Api Documentation : http://localhost/api/doc
 
