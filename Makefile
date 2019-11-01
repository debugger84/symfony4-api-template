.DEFAULT_GOAL := help
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
$(eval $(RUN_ARGS):;@:)

####################################################################################################
## MAIN COMMANDS
####################################################################################################
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'

start: dc-up composer-install doctrine-migrate ## RUN APPLICATION

test: doctrine-migrate-test run-tests ## TEST APPLICATION

analyze: ## Run static analyze tool
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&./vendor/bin/psalm"

run-command: ## Run optimization job command (example of a command)
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&./bin/console app:campaign:optimization"

####################################################################################################
# Console
####################################################################################################
console-workspace: ## Run console with php-cli as www-data
	cd ./docker && docker-compose exec --user www-data workspace bash

console-workspace-root: ## Run console with php-cli as laradock user
	cd ./docker && docker-compose exec --user laradock workspace bash

console-nginx: ## Run console with nginx as root
	cd ./docker && docker-compose exec nginx bash

console-php-fpm: ## Run console with php-fpm as root
	cd ./docker && docker-compose exec php-fpm bash

####################################################################################################
# Composer
####################################################################################################
composer-require: ## Run console with php-cli as www-data
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&composer require $(RUN_ARGS)"

composer-install: ## Run console with php-cli as www-data
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&composer install"

####################################################################################################
# Helpers
####################################################################################################
doctrine-diff: ## Run doctrine diff
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&./bin/console doctrine:migration:diff $(RUN_ARGS)"

doctrine-migrate: ## Run doctrine migrations
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&./bin/console doctrine:migration:migrate $(RUN_ARGS)" --no-interaction

doctrine-migrate-test: ## Run doctrine migrations on test database
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&./bin/console doctrine:migration:migrate $(RUN_ARGS) --no-interaction --env=test"

run-tests: ## Run all tests using the Codeception framework
	cd ./docker && docker-compose exec --user laradock workspace  sh -c  "cd /var/www&&./vendor/bin/codecept run $(RUN_ARGS)"

####################################################################################################
# Containers management with docker-compose (dc)
####################################################################################################
dc-up: ## Run necessary containers with docker-compose.yml
	cd ./docker && docker-compose up -d nginx mysql phpmyadmin

dc-down: ## Stop and delete containers from docker-compose.yml
	cd ./docker && docker-compose down

dc-stop: ## Stop docker containers from docker-compose.yml
	cd ./docker && docker-compose stop

dc-start: ## Start stopped containers docker-compose.yml
	cd ./docker && docker-compose start

dc-ps: ## List of containers
	cd ./docker && docker-compose ps
