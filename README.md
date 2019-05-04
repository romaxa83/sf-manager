# Symfony4 with Docker

Стартовый symfony-skeleton разворачиваемый на docker.
Стек:
  - Nginx
  - Php 7.2
  - Postgresql 11.2
  - PgAdmin
### Использование
развертывание приложения.

```sh
$ make init
```
использование консольных команд symfony.

```sh
$ docker-compose run --rm app-php-cli php bin/console <command>
```
pgadmin находиться по адресу http://localhost:555
вход
| Поля | Данные |
| ------ | ------ |
| login | pgadmin4@pgadmin.org |
| password | admin |
подключение к серверу
| Поля | Данные |
| ------ | ------ |
| host name/address | app-postgres |
| port | 5432 |
| maintenance | app |
| username | app |
| password | secret |
