# Symfony4 with Docker

#####Стартовый symfony-skeleton разворачиваемый на docker.
Стек:
  - Nginx
  - Php 7.2
  - Postgresql 11.2
  - PgAdmin
  - MailHog
#### Использование
развертывание приложения.

```sh
$ make init
```
подгрузка фикстур(фейковые данные)
```sh
$ make load-fixture
```
запуск тестов
```sh
$ make test
```
использование консольных команд symfony.

```sh
$ docker-compose run --rm app-php-cli php bin/console <command>
```
##### База данных
используеться **postgesql**

pgadmin доступна по адресу http://localhost:8082
вход
- login : ***pgadmin4@pgadmin.org***
- password : ***admin***

подключение к серверу

- host name/address : ***app-postgres***
- port : ***5432***
- maintenance : ***app***
- username : ***app***
- password : ***secret***

##### Почтовик
используеться mailhog (для разработки)

доступен по адресу http://localhost:8081