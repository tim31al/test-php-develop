##################
# Variables
##################

DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_PHP = docker compose exec -u app php-fpm

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
	${DOCKER_COMPOSE_PHP} bash

cache:
	${DOCKER_COMPOSE_PHP} php bin/console cache:clear
	${DOCKER_COMPOSE_PHP} php bin/console cache:clear --env=test

test_db_up:
	${DOCKER_COMPOSE_PHP} php bin/console --env=test doctrine:database:create

test_db_update:
	${DOCKER_COMPOSE_PHP} php bin/console --env=test doctrine:migrations:migrate

fixtures:
	${DOCKER_COMPOSE_PHP} php bin/console --env=test doctrine:fixtures:load

test:
	${DOCKER_COMPOSE_PHP} php -d xdebug.mode=off bin/phpunit --testdox

test_func:
	${DOCKER_COMPOSE_PHP} php -d xdebug.mode=off vendor/bin/phpunit tests/Functional --testdox

test_unit:
	${DOCKER_COMPOSE_PHP} php -d xdebug.mode=off vendor/bin/phpunit tests/Unit --testdox

test_api:
	${DOCKER_COMPOSE_PHP} php -d xdebug.mode=off vendor/bin/phpunit tests/Api --testdox

##################
# Code analysis
##################
phpstan:
	${DOCKER_COMPOSE_PHP} vendor/bin/phpstan analyse src --level 9

cs_fix:
	${DOCKER_COMPOSE_PHP} vendor/bin/php-cs-fixer fix