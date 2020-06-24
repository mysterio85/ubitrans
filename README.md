##  Test technique web back

###  Installation

#### prerequisite : 

##### For Linux install Docker and Docker-compose
* [Docker](https://www.hostinger.fr/tutoriels/installer-docker-sur-ubuntu) 
* [Docker-compose](https://www.digitalocean.com/community/tutorials/how-to-install-docker-compose-on-debian-10-fr#:~:text=%C3%89tape%201%20%2D%20Installation%20de%20Docker%20Compose,-Bien%20que%20vous&text=V%C3%A9rifiez%20la%20version%20en%20cours,local%2Fbin%2Fdocker%2Dcompose) 

##### For windows install Docker and Docker-compose
* [Docker](https://docs.docker.com/docker-for-windows/install/) 
* [Docker-compose](https://docs.docker.com/compose/install/) 


To install, follow the following steps

#### Pull project
```
Pull project to git repository : git@github.com:mysterio85/ubitrans.git
```

#### Docker ( environnement ) 
```
docker-compose build 
docker-compose up -d 
```

#### Install symfony 4 with composer 
```
docker-compose exec php composer install
```

#### Create Database and tables 
##### dev : 
```
docker-compose exec php bin/console d:d:c --env=dev
docker-compose exec php bin/console d:s:u --force --env=dev 
```

##### test : 
```
docker-compose exec php bin/console d:d:c --env=test
docker-compose exec php bin/console d:s:u --force --env=test
```

#### Fixture to dev 
```
docker-compose exec php bin/console hautelook:fixtures:load
```

####  Test 
```
docker-compose exec php bin/phpunit
```

#### Links
* [Api Documentation](http://localhost/api/doc) 
* [Access Aminer](http://localhost:8080) 

```
Serveur : mysql
Utilisateur : root
mot de passe : root
Base de données dev : dev
Base de données test : test 
```
#### Endpoints
* [POST] http://localhost/api/students [ Add new student ]
* [DELETE] http://localhost/api/students/{id}  [ delete student ] 
* [PATCH] http://localhost/api/students/{id} [Edit student ]
* [GET] http://localhost/api/students/averages [ Retrieve all grade average ]
* [GET] http://localhost/api/students/{id}/averages [ Retrieve on student average ]
* [POST] http://localhost/api/students/{id}/grades [ Add new grade for student ]

