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

test:
	${DOCKER_COMPOSE} exec -u app php-fpm -d xdebug.mode=off bin/phpunit --testdox

test_unit:
	${DOCKER_COMPOSE} exec -u app php-fpm -d xdebug.mode=off vendor/bin/phpunit tests/Unit --testdox

test_api:
	${DOCKER_COMPOSE} exec -u app php-fpm -d xdebug.mode=off vendor/bin/phpunit tests/Api --testdox

