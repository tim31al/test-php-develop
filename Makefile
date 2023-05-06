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

test:
	${DOCKER_COMPOSE} exec -u app php-fpm bin/phpunit

cache:
	docker-compose -f ./docker/docker-compose.yml exec -u app php-fpm bin/console cache:clear
	docker-compose -f ./docker/docker-compose.yml exec -u app php-fpm bin/console cache:clear --env=test

