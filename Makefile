up: docker-up
init: docker-down docker-pull docker-build docker-up app-init
test: app-test	

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

#для работы с doctine
doctrine-diff:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:diff

doctrine-migrate:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction
