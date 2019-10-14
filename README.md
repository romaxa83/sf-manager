# Symfony4 with Docker

#### Тестовый проект.
Стек:
  - Nginx
  - Php 7.2
  - Postgresql 11.2
  - PgAdmin
  - MailHog
  - Redis
  - Centrifugo

Для хранения файлов используеться эмуляция ftp-сервер,который подымается
в storage (доступ http://localhost:8081)

Redis используеться для хранения сессий пользователя и для хранения очередей
сообщений 

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
использование консольных команд symfony

```sh
$ docker-compose run --rm app-php-cli php bin/console <command>
```

запуск worker (для отправки сообщзений в фоне)

```sh
$ make worker-start
```

запуск worker с выводом всех логов (для отправки сообщзений в фоне)

```sh
$ make worker-start-logs
```
##### Cenrifugo
используеться как websocket-server для сообщений в браузер
доступ к админке http://localhost:8083

##### База данных
используеться **postgesql**

pgadmin доступна по адресу http://localhost:8084

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

доступен по адресу http://localhost:8082

##### Панель для управления контейнерами
используеться portainer.io

доступен по адресу http://localhost:9000

- login : ***admin***
- password : ***root-root***

#### Описани

- после загрузки фикстур можно зайти под админом
(login:admin@admin.com;password:password)
данные пользователя при авторизации сохраняються в хранилище redis

- или зарегестрироваться (будет роль пользователя),а затем в консоли изменить роль на админ

смена роли через консоль
```sh
$ make change-role
```

потвердить пользователя через консоль
```sh
$ make confirm-user
```