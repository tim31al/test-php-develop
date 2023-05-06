##################
# Variables
##################

DOCKER_COMPOSE = docker compose

##################
# Docker compose
##################

build:
	${DOCKER_COMPOSE} build

up:
	${DOCKER_COMPOSE} up -d --remove-orphans

down:
	${DOCKER_COMPOSE} down

dc_ps:
	${DOCKER_COMPOSE} ps

dc_logs:
	${DOCKER_COMPOSE} logs -f

dc_down:
	${DOCKER_COMPOSE} down -v --rmi=all --remove-orphans

##################
# App
##################

app_bash:
	${DOCKER_COMPOSE} exec -u app php-fpm bash

cache:
	${DOCKER_COMPOSE} exec -u app php-fpm bin/console cache:clear
	${DOCKER_COMPOSE} exec -u app php-fpm bin/console cache:clear --env=test

test_db_up:
	${DOCKER_COMPOSE} exec -u app php-fpm php bin/console --env=test doctrine:database:create

test_db_update:
	${DOCKER_COMPOSE} exec -u app php-fpm php bin/console --env=test doctrine:migrations:migrate

fixtures:
	${DOCKER_COMPOSE} exec -u app php-fpm php bin/console --env=test doctrine:fixtures:load

test:
	${DOCKER_COMPOSE} exec -u app php-fpm php -d xdebug.mode=off bin/phpunit --testdox

test_func:
	${DOCKER_COMPOSE} exec -u app php-fpm php -d xdebug.mode=off vendor/bin/phpunit tests/Functional --testdox

test_unit:
	${DOCKER_COMPOSE} exec -u app php-fpm php -d xdebug.mode=off vendor/bin/phpunit tests/Unit --testdox

test_api:
	${DOCKER_COMPOSE} exec -u app php-fpm php -d xdebug.mode=off vendor/bin/phpunit tests/Api --testdox

