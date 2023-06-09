##################
# Variables
##################

DOCKER_COMPOSE = docker compose --env-file .env.docker
DOCKER_COMPOSE_PHP = ${DOCKER_COMPOSE} exec -u app php-fpm

##################
# Docker compose
##################

build:
	${DOCKER_COMPOSE} build

dc_up:
	${DOCKER_COMPOSE} up -d --remove-orphans

dc_down:
	${DOCKER_COMPOSE} down

dc_ps:
	${DOCKER_COMPOSE} ps

dc_logs:
	${DOCKER_COMPOSE} logs -f

dc_drop:
	${DOCKER_COMPOSE} down -v

##################
# App
##################
init: app_init test_init

app_init: dc_up install db_update keypair fixtures

install:
	docker compose --env-file .env.docker exec php-fpm composer install

app_bash:
	${DOCKER_COMPOSE_PHP} bash

cache:
	${DOCKER_COMPOSE_PHP} php bin/console cache:clear
	${DOCKER_COMPOSE_PHP} php bin/console cache:clear --env=test

fixtures:
	${DOCKER_COMPOSE_PHP} php bin/console doctrine:fixtures:load --group=AppFixtures -q

keypair:
	docker compose --env-file .env.docker exec php-fpm php bin/console lexik:jwt:generate-keypair

db_update:
	${DOCKER_COMPOSE_PHP} php bin/console doctrine:migrations:migrate -q

##################
# App Test
##################

test_init: test_db_up test_db_update test_fixtures

test_db_up:
	${DOCKER_COMPOSE_PHP} php bin/console --env=test doctrine:database:create

test_db_update:
	${DOCKER_COMPOSE_PHP} php bin/console --env=test doctrine:migrations:migrate -q

test_fixtures:
	${DOCKER_COMPOSE_PHP} php bin/console --env=test doctrine:fixtures:load --group=TestFixtures -q

test:
	${DOCKER_COMPOSE_PHP} php -d xdebug.mode=coverage bin/phpunit --testdox --coverage-html var/coverage/ --coverage-cache var/cache/coverage/

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
	${DOCKER_COMPOSE_PHP} vendor/bin/phpstan analyse -c phpstan.neon

cs_fix:
	${DOCKER_COMPOSE_PHP} vendor/bin/php-cs-fixer fix