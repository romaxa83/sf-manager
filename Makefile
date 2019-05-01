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

#сборка для продакта
build-production:
	docker build --pull --file=app/docker/prod/nginx.docker --tag ${REGISTRY_ADDRESS}/app-nginx:${IMAGE_TAG} manager
	docker build --pull --file=app/docker/prod/php-fpm.docker --tag ${REGISTRY_ADDRESS}/app-php-fpm:${IMAGE_TAG} manager
	docker build --pull --file=app/docker/prod/php-cli.docker --tag ${REGISTRY_ADDRESS}/app-php-cli:${IMAGE_TAG} manager
	docker build --pull --file=app/docker/prod/postgres.docker --tag ${REGISTRY_ADDRESS}/app-postgres:${IMAGE_TAG} manager
	docker build --pull --file=app/docker/prod/redis.docker --tag ${REGISTRY_ADDRESS}/app-redis:${IMAGE_TAG} manager

push-production:
	docker push ${REGISTRY_ADDRESS}/app-nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/app-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/app-php-cli:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/app-postgres:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/app-redis:${IMAGE_TAG}

deploy-production:
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'rm -rf docker-compose.yml .env'
	scp -o StrictHostKeyChecking=no -P ${PRODUCTION_PORT} docker-compose-production.yml ${PRODUCTION_HOST}:docker-compose.yml
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_APP_SECRET=${MANAGER_APP_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_DB_PASSWORD=${MANAGER_DB_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_REDIS_PASSWORD=${MANAGER_REDIS_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_OAUTH_FACEBOOK_SECRET=${MANAGER_OAUTH_FACEBOOK_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose pull'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose --build -d'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'until docker-compose exec -T manager-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate --no-interaction'

