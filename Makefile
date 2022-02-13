USER_ID := $(shell id -u)
GROUP_ID := $(shell id -g)
COMPOSE_EXEC := docker-compose exec --user $(USER_ID):$(GROUP_ID)

.PHONY: help
help: ## Справка по командам
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z1-9_-]+:.*?## / {printf "  \033[32m%-16s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: build
build: ## Билд образа
	@docker-compose build

.PHONY: start
start: up vendor ## Старт приложения

.PHONY: stop
stop: ## Стоп приложения
	@docker-compose down

.PHONY: clean
clean: ## Стоп приложения и очистка файлов
	@docker-compose down -v
	@rm -rf vendor
	@rm -rf var

.PHONY: up
up:
	@docker-compose up -d

vendor: composer.json composer.lock
	@$(COMPOSE_EXEC) app composer install
	@touch -c vendor

.PHONY: sh
sh: ## Войти в консоль контейнера
	@$(COMPOSE_EXEC) app sh

.PHONY: tests
tests: ## Запуск тестов
	@$(COMPOSE_EXEC) app vendor/bin/phpunit

.PHONY: psalm
psalm: ## Запуск статического анализатора кода
	@$(COMPOSE_EXEC) app vendor/bin/psalm

.PHONY: cs-fixer
cs-fixer: ## Запустить PHP CS Fixer и исправить codestyle
	@$(COMPOSE_EXEC) app vendor/bin/php-cs-fixer fix --using-cache no --using-cache=no

.PHONY: cs-fixer-test
cs-fixer-test: ## Запустить PHP CS Fixer и показать diff (не исправлять)
	@$(COMPOSE_EXEC) app vendor/bin/php-cs-fixer fix --dry-run --diff --using-cache=no --show-progress=dots
