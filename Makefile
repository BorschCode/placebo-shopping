.DEFAULT_GOAL := help
DOCKER_COMPOSE = docker compose
APP = $(DOCKER_COMPOSE) exec app

##@ Build

build-dev: ## Build development image
	$(DOCKER_COMPOSE) build --pull app

build-dev-fresh: ## Force rebuild development image (no cache)
	$(DOCKER_COMPOSE) build --no-cache --pull app

build-prod: ## Build production image
	$(DOCKER_COMPOSE) -f compose.yaml -f compose.prod.yaml build --pull app

build-prod-fresh: ## Force rebuild production image (no cache)
	$(DOCKER_COMPOSE) -f compose.yaml -f compose.prod.yaml build --no-cache --pull app

##@ Stack

up: ## Start dev stack (detached, wait for healthy)
	$(DOCKER_COMPOSE) up -d --wait

up-prod: ## Start production stack
	$(DOCKER_COMPOSE) -f compose.yaml -f compose.prod.yaml up -d --wait

down: ## Stop and remove containers
	$(DOCKER_COMPOSE) down --remove-orphans

restart: ## Restart the app container
	$(DOCKER_COMPOSE) restart app

logs: ## Tail logs (app by default; pass s=rabbitmq etc. to filter)
	$(DOCKER_COMPOSE) logs -f $(s)

##@ Application

shell: ## Open a shell inside the app container
	$(APP) bash

console: ## Run a Symfony console command — usage: make console c="cache:clear"
	$(APP) bin/console $(c)

migrate: ## Run database migrations
	$(APP) bin/console doctrine:migrations:migrate --no-interaction

diff: ## Generate a new migration from entity changes
	$(APP) bin/console doctrine:migrations:diff

schema-validate: ## Validate Doctrine schema
	$(APP) bin/console -e test doctrine:schema:validate

cc: ## Clear the Symfony cache
	$(APP) bin/console cache:clear

##@ Tests

test: ## Run the full PHPUnit suite
	$(APP) bin/phpunit

test-filter: ## Run tests matching a filter — usage: make test-filter f=MyTest
	$(APP) bin/phpunit --filter $(f)

##@ Composer

composer-install: ## Install PHP dependencies
	$(APP) composer install

composer-require: ## Add a package — usage: make composer-require p="vendor/pkg"
	$(APP) composer require $(p)

##@ Help

help: ## Show this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) }' $(MAKEFILE_LIST)
