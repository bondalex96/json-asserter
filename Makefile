php-sh: ## Connect to shell of php container
	docker-compose exec php-cli sh

init: docker-init composer-install ## Init lib
docker-init: docker-pull docker-build docker-up

test: ## Run tests
	docker-compose run --rm php-cli vendor/bin/phpunit

docker-build:
	docker-compose build

docker-up:
	docker-compose up

docker-pull:
	docker-compose pull

composer-install:
	docker-compose run --rm --no-deps php-cli composer install
