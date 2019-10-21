up: docker-up
init: docker-down docker-pull docker-build docker-up app-init cp-env permission doctrine-migrate manager-oauth-keys
test: app-test

cp-env:
	cp app/.env.example app/.env

permission:
	sudo chmod 777 -R app/var

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans #очистит все запущеные контейнеры

#остановит все запущеные контейнеры
dockers-stop:	
	docker stop $(docker ps -a -q)

#покажит все контейнеры
dockers-show:	
	docker ps -a

#покажит все запущеные контейнеры
dockers-run:	
	docker ps			

docker-pull:
	docker-compose pull	

docker-build:
	docker-compose build

#запускаем тесты
app-test:
	docker-compose run --rm app-php-cli php bin/phpunit	

app-init: app-composer-install

app-composer-install:
	docker-compose run --rm app-php-cli composer install

cli:
	docker-compose run --rm app-php-cli php bin/app.php

console:
	docker-compose run --rm app-php-cli php bin/console

#для работы с doctine
doctrine-diff:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:diff

doctrine-migrate:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction

doctrine-permission:
	sudo chmod 777 -R app/var

load-fixture:
	docker-compose run --rm app-php-cli php bin/console doctrine:fixtures:load --no-interaction

#для работа с webpack
webpack-init:
	docker-compose run --rm app-php-cli composer require encore
	sudo chmod 777 -R assets
	sudo chmod 777 package.json
	sudo chmod 777 webpack.config.js

npm-install:
	docker-compose run --rm app-nodejs npm install

webpack-build:
	docker-compose run --rm app-nodejs ./node_modules/.bin/encore dev

webpack-watch:
	docker-compose run --rm app-nodejs ./node_modules/.bin/encore dev --watch

confirm-user:
	docker-compose run --rm app-php-cli php bin/console user:confirm

change-role:
	docker-compose run --rm app-php-cli php bin/console user:role

#запуск воркера для отправки сообщений в фоне
worker-start:
	docker-compose run --rm app-php-cli php bin/console messenger:consume async

worker-start-logs:
	docker-compose run --rm app-php-cli php bin/console messenger:consume async -vv

#генерация ключей для oauth2
manager-oauth-keys:
	docker-compose run --rm app-php-cli mkdir -p var/oauth
	docker-compose run --rm app-php-cli openssl genrsa -out var/oauth/private.key 2048
	docker-compose run --rm app-php-cli openssl rsa -in var/oauth/private.key -pubout -out var/oauth/public.key
	docker-compose run --rm app-php-cli chmod 644 var/oauth/private.key var/oauth/public.key

#генерация документации для api
api-docks:
	docker-compose run --rm app-php-cli php bin/console api:docs